<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

$this->import('
    create-account
    mapas-breadcrumb
');

$this->breadcrumb = [
    ['label'=> i::__('Voltar para entrar na conta'), 'url' => $app->createUrl('auth')],
];
?>

<?php if (isset($config['google-recaptcha-sitekey'])) : ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
<?php endif; ?>

<mapas-breadcrumb></mapas-breadcrumb>

<create-account config='<?= $configs; ?>'></create-account>