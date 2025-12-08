<?php

use MapasCulturais\App;
use Curl\Curl;

class DecidimStrategy extends OpauthStrategy{

	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = ['client_id', 'client_secret', 'auth_endpoint'];
	/**
	 * Optional config keys, without predefining any default values.
	 */
	public $optionals = ['redirect_uri', 'scope', 'response_type', 'register_form_action', 'register_form_method'];
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 * eg. array('scope' => 'email');
	 */
	public $defaults = ['redirect_uri' => '{complete_url_to_strategy}oauth2callback'];

	/**
	 * Auth request
	 */
	public function request(){
		$url = $this->strategy['auth_endpoint'];
		$params = array(
			'client_id' => $this->strategy['client_id'],
			'client_secret' => $this->strategy['client_secret'],
			'redirect_uri' => $this->strategy['redirect_uri'],
			'response_type' => 'code',
			'scope' => $this->strategy['scope']
		);
		foreach ($this->optionals as $key){
			if (!empty($this->strategy[$key])) $params[$key] = $this->strategy[$key];
		}
		
		$this->clientGet($url, $params);
	}
	
	/**
	 * Internal callback, after OAuth
	 */
	public function oauth2callback(){
		if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
			$code = $_GET['code'];
			$url = $this->strategy['token_endpoint'];
			$params = array(
				'code' => $code,
				'client_id' => $this->strategy['client_id'],
				'client_secret' => $this->strategy['client_secret'],
				'redirect_uri' => $this->strategy['redirect_uri'],
				'grant_type' => 'authorization_code'
			);
			$response = $this->serverPost($url, $params, null, $headers);
			
			$results = json_decode($response);
			
			if (!empty($results) && !empty($results->access_token)){
				
				$userinfo = $this->userinfo($results->access_token);
				
				
				$this->auth = array(
					'uid' => $userinfo['id'],
					'info' => array(),
					'credentials' => array(
						'token' => $results->access_token,
						'expires' => date('c', time() + $results->expires_in)
					),
					'raw' => $userinfo
				);

				
				if (!empty($results->refresh_token))
				{
					$this->auth['credentials']['refresh_token'] = $results->refresh_token;
				}
				
				$this->mapProfile($userinfo, 'name', 'info.name');
				$this->mapProfile($userinfo, 'email', 'info.email');
				$this->mapProfile($userinfo, 'given_name', 'info.first_name');
				$this->mapProfile($userinfo, 'family_name', 'info.last_name');
				$this->mapProfile($userinfo, 'picture', 'info.image');
				
				$this->callback();
			}
			else{
				$error = array(
					'code' => 'access_token_error',
					'message' => 'Failed when attempting to obtain access token',
					'raw' => array(
						'response' => $response,
						'headers' => $headers
					)
				);
				$this->errorCallback($error);
			}
		}
		else{
			$error = array(
				'code' => 'oauth2callback_error',
				'raw' => $_GET
			);
			
			$this->errorCallback($error);
		}
	}
	
	/**
	 * Queries Google API for user info
	 *
	 * @param string $access_token 
	 * @return array Parsed JSON results
	 */
	private function userinfo($access_token){
		$options = [
			'http' => [
				'header'        => "Authorization: Bearer {$access_token}\r\nAccept: application/json",
				'ignore_errors' => true,
				'method'        => 'GET'
			]
		];
		
		// Alterado para passar os headers corretamente e manter o uso do serverGet
		$userinfo = $this->serverGet($this->strategy['userinfo_endpoint'], [], $options, $responseHeaders);
		// $userinfo = $this->serverGet($this->strategy['userinfo_endpoint'], array('access_token' => $access_token), null, $headers);

		if (!empty($userinfo)){
			return $this->recursiveGetObjectVars(json_decode($userinfo));
		}
		else{
			$error = array(
				'code' => 'userinfo_error',
				'message' => 'Failed when attempting to query for user information',
				'raw' => array(
					'response' => $userinfo,
					'headers' => $headers
				)
			);
			$this->errorCallback($error);
		}
	}

	/**
	 * Atualiza dados do usuário autenticado a partir da resposta da estratégia Decidim.
	 *
	 * @param \MapasCulturais\Entities\User $user Usuário autenticado que terá os dados atualizados.
	 * @param array $response Resposta completa retornada pela estratégia Decidim.
	 * @return void
	 */
	public static function verifyUpdateData($user, $response)
	{
		$app = App::i();

		$userinfo = (object) $response['auth']['raw'];

		self::getFile($user->profile, $userinfo->image);
	}

	/**
	 * Faz o download de uma imagem remota e salva como avatar para o agente informado.
	 *
	 * @param \MapasCulturais\Entities\Agent $owner Agente proprietário do avatar.
	 * @param string|null $url URL da imagem a ser baixada.
	 * @return void
	 */
	public static function getFile($owner, $url){

		$curl = new Curl;
		$curl->get($url);
		$curl->close();
		$response = $curl->response;

		if(mb_strpos($response, 'não encontrada')){
			return;
		}
		
		$tmp = tempnam("/tmp", "");
		$handle = fopen($tmp, "wb");
		fwrite($handle,$response);
		fclose($handle);

		 // Confere MIME e extensões aceitas
		 if (!self::checkFileType($tmp)) {
            unlink($tmp);
            return;
        }

		$mime = mime_content_type($tmp) ?: 'application/octet-stream';

        $extension = match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png'               => 'png',
            'image/gif'               => 'gif',
            'image/webp'              => 'webp',
            default                   => null,
        };

		if(!$extension) {
			unlink($tmp);
			return;
		}
		
		$basename = sprintf('%s.%s', md5(uniqid('', true)), $extension);

		$class_name = $owner->fileClassName;
		
		$file = new $class_name([
			"name" => $basename,
			"type" => $mime,
			"tmp_name" => $tmp,
			"error" => 0,
			"size" => filesize($tmp)
		]);

		$file->group = "avatar";
		$file->owner = $owner;
		$file->save(true);

		if(is_file($tmp)) {
			unlink($tmp);
		}
	}

	/**
	 * Verifica se um arquivo temporário corresponde a um formato de imagem suportado.
	 *
	 * @param string $filename Caminho absoluto do arquivo temporário a ser verificado.
	 * @return bool Retorna true se o arquivo for uma imagem suportada; caso contrário, false.
	 */
	public static function checkFileType($filename)
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimetype = finfo_file($finfo, $filename);
		if ($mimetype == 'image/jpg' || $mimetype == 'image/jpeg' || $mimetype == 'image/gif' || $mimetype == 'image/png') {
			$is_image = true;
		} else {
			$is_image = false;
		}

		return $is_image;
	}
	
}
