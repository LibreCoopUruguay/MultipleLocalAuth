<?php

use Curl\Curl;
use MapasCulturais\App;

class GovBrStrategy extends OpauthStrategy
{

	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = ['client_id',  'auth_endpoint', 'token_endpoint'];
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
	public function request()
	{
		$_SESSION['govbr-state'] = md5($this->strategy['state_salt'].time());

		$url = $this->strategy['auth_endpoint'];
		$params = array(
			'client_id' => $this->strategy['client_id'],
			'redirect_uri' => $this->strategy['redirect_uri'],
			'response_type' => 'code',
			'scope' => $this->strategy['scope'],
			'state' => $_SESSION['govbr-state'],
			'code_challenge' => $this->strategy['code_challenge'],
			'code_challenge_method' => $this->strategy['code_challenge_method'],
		);

		foreach ($this->optionals as $key) {
			if (!empty($this->strategy[$key])) $params[$key] = $this->strategy[$key];
		}

		$this->clientGet($url, $params);
	}

	/**
	 * Internal callback, after OAuth
	 */
	public function oauth2callback()
	{
		$app = App::i();
		$self = $this;

		if ((array_key_exists('code', $_GET) && !empty($_GET['code'])) && (array_key_exists("state", $_GET) && $_GET['state'] == $_SESSION['govbr-state'])) {
			
			$code = $_GET['code'];
		
			$url = $this->strategy['token_endpoint'];
			$params = array(
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => $this->strategy['redirect_uri'],
				'code_verifier' => $this->strategy['code_verifier'],
			);

			$token = base64_encode("{$this->strategy['client_id']}:{$this->strategy['client_secret']}");
			$curl = new Curl;
			$curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
			$curl->setHeader('Authorization', "Basic {$token}");

			$curl->post($url, $params);
			$curl->close();
			$response = $curl->response;

			$results = json_decode($response);

			if (!empty($results) && !empty($results->id_token)) {

				/** @var stdClass $userinfo */
				$userinfo = $this->userinfo($results->id_token);
				$userinfo->access_token =  $results->access_token;

				$exp_name = explode(" ", $userinfo->name);
			
				$info = [
					'name' => $exp_name[0],
					'cpf' => $userinfo->sub,
					'email' => $userinfo->email_verified ? $userinfo->email : null,
					'phone_number' => $userinfo->phone_number_verified ? $userinfo->phone_number : null,
				];
				
				$this->auth = array(
					'uid' => $userinfo->jti,
					'credentials' => array(
						'token' => $results->id_token,
						'expires' => $userinfo->exp
					),
					'raw' => $userinfo,
					'info' => $info
				);
		
			
				$this->callback();
			} else {
				$error = array(
					'code' => 'access_token_error',
					'message' => 'Failed when attempting to obtain access token',
					'raw' => array(
						'response' => $response,
					)
				);
				$this->errorCallback($error);
			}
		} else {
			$error = array(
				'code' => 'oauth2callback_error',
				'raw' => $_GET
			);

			$this->errorCallback($error);
		}
	}

	/**
	 * @param string $id_token 
	 * @return array Parsed JSON results
	 */
	private function userinfo($id_token)
	{
		$exp = explode(".", $id_token);
		return json_decode(base64_decode($exp[1]));
	}

	public static function getFile($owner, $url, $token){

		$curl = new Curl;
		$curl->setHeader('Authorization', "Bearer {$token}");
		$curl->get($url);
		$curl->close();
		$response = $curl->response;

		$tmp = tempnam("/tmp", "");
		$handle = fopen($tmp, "wb");
		fwrite($handle,$response);
		fclose($handle);

		$class_name = $owner->fileClassName;

		$basename = md5(time()).".jpg";

		$file = new $class_name([
			"name" => $basename,
			"type" => mime_content_type($tmp),
			"tmp_name" => $tmp,
			"error" => 0,
			"size" => filesize($tmp)
		]);

		$file->group = "avatar";
		$file->owner = $owner;
		$file->save(true);
	}

	public static function applySeal($user, $response){
		$app = App::i();

		$agent = $user->profile;
		$sealId = $response['auth']['applySeal'];

		if($sealId){
			$app->disableAccessControl();

			$seal = $app->repo('Seal')->find($sealId);
			$relations = $agent->getSealRelations();

			$has_new_seal = false;
			foreach($relations as $relation){
				if($relation->seal->id == $seal->id){
					$has_new_seal = true;
					break;
				}
			}

			if(!$has_new_seal){
				$agent->createSealRelation($seal);
			}
			
			$app->enableAccessControl();

		}
	}

	public static function verifyUpdateData($user, $response)
	{
		$app = App::i();
		
		$userinfo = (object) $response['auth']['raw'];
		$app->disableAccessControl();
		$user->profile->nomeCompleto = $userinfo->name;
		$user->profile->save(true);
		self::getFile($user->profile, $userinfo->picture, $userinfo->access_token);

		$app->enableAccessControl();
	}
}
