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

    <!-- Login action -->

    <div v-if="!recoveryRequest && !recoveryMode" class="login__action">
        <mapas-card>
            <template #title>
                <label> <?= i::__('Boas vindas!') ?> </label>
                <p> <?= i::__('Entre na sua conta do ' .$app->view->dict('site: name', false)) ?> </p>
            </template>
            <template #content>
                <form class="grid-12" @submit.prevent="doLogin();">
                    <div class="field col-12">
                        <label for="email"> <?= i::__('E-mail ou CPF') ?> </label>
                        <input type="text" name="email" id="email" v-model="email" autocomplete="off" />
                    </div>
                    <div class="field col-12">
                        <label for="password"> <?= i::__('Senha') ?> </label>
                        <input type="password" name="password" id="password" v-model="password" autocomplete="off" />
                        <a id="multiple-login-recover" class="recover" @click="recoveryRequest = true"> <?= i::__('Esqueci minha senha') ?> </a>
                    </div>                    
                    <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" @render="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>
                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Entrar') ?> </button>
                    
                    <div class="divider col-12"></div>

                    <div class="social-login col-12">
                        <a v-if="configs.strategies.govbr?.visible" class="social-login--button button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>">                                
                            <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
                            <?= i::__('Entrar com Gov.br') ?>                            
                        </a>
                        <a v-if="configs.strategies.Google?.visible" class="social-login--button button button--icon button--large button--md google" href="<?php echo $app->createUrl('auth', 'google') ?>">                                
                            <div class="img"> <img height="16" src="<?php $this->asset('img/g.png'); ?>" /> </div>                                
                            <?= i::__('Entrar com Google') ?>
                        </a>
                        <h1 v-if="configs.strategies.Google?.visible || configs.strategies.govbr?.visible" class="col-12">OU</h1>
                        <div class="create col-12">
                            <small> <?= i::__('Crie sua conta para começar a usar o Mapas agora mesmo.') ?> </small>    
                            <a class="col-12 button button--primary button--large button--md" href="<?php echo $app->createUrl('auth', 'register') ?>"> 
                                <?= i::__('Criar conta') ?>
                            </a>
                        </div>
                    </div>
                </form>
            </template>
        </mapas-card>
    </div>

    <!-- Recovery request -->

    <div v-if="recoveryRequest" class="login__recovery--request">
        <mapas-card v-if="!recoveryEmailSent">
            <template #title>
                <label> <?= i::__('Alteração de senha') ?> </label>
                <p> <?= i::__('Se você esqueceu a senha, não se preocupe, todo mundo passa por isso.') ?> <br> <?= i::__('Digite seu e-mail para criar uma nova.') ?> </p>
            </template>
            <template #content>
                <form class="grid-12" @submit.prevent="requestRecover();">
                    <div class="field col-12">
                        <label for="email"> <?= i::__('E-mail') ?> </label>
                        <input type="email" name="email" id="email" v-model="email" autocomplete="off" />
                    </div>
                    <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" @render="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>
                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Alterar senha') ?> </button>
                    <a @click="recoveryRequest = false" class="col-12 button button--secondarylight button--large button--md"> <?= i::__('Voltar') ?> </a>
                </form>
            </template>
        </mapas-card>

        <mapas-card class="no-title" v-if="recoveryEmailSent">
            <template #content>
                <div class="grid-12">
                    <div class="col-12 header">
                        <label class="header__title"> <?= i::__('Alteração de senha') ?> </label>
                        <mc-icon name="circle-checked" class="header__icon"></mc-icon>
                        <label class="header__label"> <?= i::__('Enviamos as instruções de alteração de senha para seu e-mail.') ?> </label>
                    </div>

                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Não recebi o e-mail') ?> </button>
                    <a @click="recoveryEmailSent = false" class="col-12 button button--secondarylight button--large button--md"> <?= i::__('Voltar') ?> </a>
                </div>
            </template>
        </mapas-card>
    </div>

    <!-- Recovery action -->

    <div v-if="recoveryMode" class="login__recovery--action">
        <mapas-card>
            <template #title>
                <label> <?= i::__('Redefinir senha de acesso') ?> </label>
            </template>
            <template #content>
                <form class="grid-12" @submit.prevent="doRecover();">

                    <div class="field col-12 password">
                        <label for="pwd"> <?= i::__('Senha'); ?> </label>
                        <input autocomplete="off" id="pwd" type="password" name="password" v-model="password" />
                        <span class="password-rules">
                            <?= i::__('A senha deve ter:') ?>
                            <strong> {{passwordRules.minimumPasswordLength}}<?= i::__(' caracteres, um número, um caractere especial (! @ # $ & *), pelo menos uma letra maiúscula e uma minúscula.') ?></strong>
                        </span>
                    </div>

                    <div class="field col-12 password">
                        <label for="pwd"> <?= i::__('Senha'); ?> </label>
                        <input autocomplete="off" id="pwd" type="password" name="confirmPassword" v-model="confirmPassword" />
                    </div>

                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Redefinir senha') ?> </button>
                </form>
            </template>
        </mapas-card>
    </div>

</div>
