<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mapas-card
');
?>

<div class="login">
    <mapas-card>
        <template #title>
            <label> <?= i::__('Boas vindas!') ?> </label>
            <p> <?= i::__('Entre na sua conta do ' .$app->view->dict('site: name', false)) ?> </p>
        </template>
        <template #content>
            <!-- action="<?php //echo $login_form_action; ?>" -->
            <form class="grid-12" @submit.prevent="doLogin();">

                <div class="field col-12">
                    <label for="login"> <?= i::__('E-mail ou CPF') ?> </label>
                    <!-- value="<?php //echo htmlentities($triedEmail); ?>" -->
                    <input type="text" name="login" id="login" v-model="login" autocomplete="off" />
                </div>

                <?php
                /**
                 * @todo Criar tela de recuperação de senha
                 */
                ?>
                <div class="field col-12">
                    <label for="password"> <?= i::__('Senha') ?> </label>
                    <input type="password" name="password" id="password" v-model="password" autocomplete="off" />
                    <a id="multiple-login-recover" class="multiple-recover-link" href="<?php echo $app->createUrl('auth', 'recover') ?>"> <?= i::__('Esqueci minha senha') ?> </a>
                </div>

                <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>

                <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Entrar') ?> </button>

                <div class="divider col-12"></div>

                <div class="social-login col-12">
                    <a v-if="configs.strategies.govbr.visible" class="social-login--button button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>">                                
                        <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
                        <?= i::__('Entrar com Gov.br') ?>                            
                    </a>
                    
                    <a v-if="configs.strategies.Google.visible" class="social-login--button button button--icon button--large button--md google" href="<?php echo $app->createUrl('auth', 'google') ?>">                                
                        <div class="img"> <img height="16" src="<?php $this->asset('img/g.png'); ?>" /> </div>                                
                        <?= i::__('Entrar com Google') ?>
                    </a>

                    <h1 v-if="configs.strategies.Google.visible || configs.strategies.govbr.visible" class="col-12">OU</h1>

                    <div class="create col-12">
                        <small class="col-12"> <?= i::__('Crie sua conta para começar a usar o Mapas agora mesmo.') ?> </small>    
                        <a class="col-12 button button--primary button--large button--md" href="<?php echo $app->createUrl('auth', 'register') ?>"> 
                            <?= i::__('Criar conta') ?>
                        </a>
                    </div>
                </div>
            </form>
        </template>
    </mapas-card>
</div>
