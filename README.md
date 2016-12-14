# MultipleLocalAuth

Plugin que implementa um método de autenticação local para o Mapas Culturais, em conjunto com login via redes sociais.

## Instalação e Configuração

Faça download ou clone do plugin e coloque a pasta MultipleLocalAuth no pasta dos plugins do Mapas Culturais.

No arquivo de configuração do Mapas Culturais, config.php, você deve:

1. Ativar o plugin
2. Configurar MultipleLocalAuth como seu Provider de autenticação
3. Configurar as chaves das redes sociais

Para ativar o plugin, adicione na sua array de Plugins:
```
'plugins' => [
    // ... outros plugins
    'MultipleLocalAuth' => [
        'namespace' => 'MultipleLocalAuth',
    ],
],
```

Para definir este plugin como seu método de autenticação, defina a configuraço *auth.provider*:
```
'auth.provider' => '\MultipleLocalAuth\Provider',
```

Finalmente, defina a configuração *auth.config* para definir as estratégias utilizadas e as chaves dos serviços:

```
'auth.config' => array(
    'salt' => 'LT_SECURITY_SALT_SECURITY_SALT_SECURITY_SALT_SECURITY_SALT_SECU',
    'timeout' => '24 hours',
    'strategies' => [
       'Facebook' => array(
           'app_id' => 'SUA_APP_ID',
           'app_secret' => 'SUA_APP_SECRET', 
           'scope' => 'email'
       ),

        'LinkedIn' => array(
            'api_key' => 'SUA_API_KEY',
            'secret_key' => 'SUA_SECRET_KEY',
            'redirect_uri' => URL_DO_SEU_SITE . '/autenticacao/linkedin/oauth2callback',
            'scope' => 'r_emailaddress'
        ),
        'Google' => array(
            'client_id' => 'SEU_CLIENT_ID',
            'client_secret' => 'SEU_CLIENT_SECRET',
            'redirect_uri' => URL_DO_SEU_SITE . '/autenticacao/google/oauth2callback',
            'scope' => 'email'
        ),
        'Twitter' => array(
            'app_id' => 'SUA_APP_ID', 
            'app_secret' => 'SUA_APP_SECRET', 
        ),

    ]
),
```
