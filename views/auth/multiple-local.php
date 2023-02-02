<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

$this->import('
    login
')
?>

<?php if (isset($config['google-recaptcha-sitekey'])) : ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
<?php endif; ?>

<login config='<?= $configs; ?>'></login>