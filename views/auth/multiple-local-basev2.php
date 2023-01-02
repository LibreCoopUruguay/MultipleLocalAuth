<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$this->jsObject['labels']['multiplelocal'] = $jsLabelsInternationalization;

function showStrategy($name, $config)
{

    if (!isset($config['strategies'])) {
        return false;
    }

    if (!isset($config['strategies'][$name])) {
        return false;
    }

    if (isset($config['strategies'][$name]['visible'])) {
        if (!$config['strategies'][$name]['visible'] || $config['strategies'][$name]['visible'] === 'false') {
            return false;
        } else {
            return true;
        }
    }

    return false;
}

$this->import('
    mapas-card
    mapas-container
')
?>

<?php if (isset($config['google-recaptcha-sitekey'])) : ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<div class="login">
    <mapas-card>
        <template #title>
            <label>
                <?= i::__('Boas vindas!') ?>
            </label>
            <p>
                <?= i::__('Entre na sua conta do Mapas Culturais') ?>
            </p>
        </template>
        <template #content>
            <form class="grid-12" action="<?php echo $login_form_action; ?>" method="POST">
                <div class="field col-12">
                    <label for="email"> <?= i::__('E-mail ou CPF') ?> </label>
                    <input type="text" name="email" id="email" value="<?php echo htmlentities($triedEmail); ?>" />
                </div>
                <div class="field col-12">
                    <label for="password"> <?= i::__('Senha') ?> </label>
                    <input type="password" name="password" id="password" value=""/>
                    <a id="multiple-login-recover" class="multiple-recover-link" href=""> <?= i::__('Esqueci minha senha') ?> </a>
                </div>

                <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                    <div class="g-recaptcha col-12" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                <?php endif; ?>

                <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Entrar') ?> </button>

                <div class="divider col-12"></div>

                <div class="social-login col-12">

                    <?php if (showStrategy('Google', $config)) : ?>
                        <a class="social-login--button button button--icon button--large button--md google" href="<?php echo $app->createUrl('auth', 'google') ?>">                                
                            <div class="img"> <img height="16" src="<?php $this->asset('img/g.png'); ?>" /> </div>                                
                            <?= i::__('Entrar com Google') ?>
                        </a>
                    <?php endif; ?>

                    <?php if (showStrategy('govbr', $config)) : ?>
                        <a class="social-login--button button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>">                                
                            <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
                            <?= i::__('Entrar com Gov.br') ?>                            
                    </a>
                    <?php endif; ?>

                    <h1 class="col-12">OU</h1>

                    <div class="create col-12">
                        <small class="col-12"> <?= i::__('Crie sua conta para começar a usar o Mapas agora mesmo.') ?> </small>    
                        <a class="col-12 button button--primary button--large button--md" href="<?php echo $app->createUrl('auth', 'createUser') ?>"> 
                            <?= i::__('Criar conta') ?>
                        </a>
                    </div>

                </div>

            </form>
        </template>
    </mapas-card>
</div>