<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    entity-field
    entity-terms
    mapas-card
    mc-icon
');
?>

<div class="create-account"> 

    <div v-if="!created" class="create-account__title">
        <label> <?= i::__('Criar uma conta') ?> </label>
        <p> <?= i::__('Siga os passos para criar o seu cadastro no Mapa da Cultura Brasileira.') ?> </p>
    </div>

    <!-- Creating account -->
    <mapas-card v-if="!created" class="no-title">        
        <template #content> 

            <?php
            /**
             * @todo Criar componente timeline
             */
            ?>

            <!-- First step -->
            <div v-if="step==1" class="create-account__step grid-12">
                <label class="title col-12"><?= i::__('Crie sua conta no Mapas') ?></label>

                <form class="col-12 grid-12" @submit.prevent="nextStep();">
                    <div class="field col-12">
                        <label for="email"> <?= i::__('E-mail') ?> </label>
                        <input type="text" name="email" id="email" v-model="email" />
                    </div>
                    <div class="field col-12">
                        <label class="document-label" for="cpf"> 
                            <?= i::__('CPF') ?> 
                            <div class="question">
                                <VMenu class="popover">
                                    <button tabindex="-1" class="question" type="button"> <?= i::__('Por que pedimos este dado') ?> <mc-icon name="question"></mc-icon> </button>
                                    <template #popper>
                                        <?= i::__('Texto sobre o motivo da coleta do CPF') ?>
                                    </template>
                                </VMenu>
                            </div>
                        </label>
                        <input type="text" name="cpf" id="cpf" v-model="cpf" @change="cpfMask" maxlength="14" />
                    </div>
                    <div class="field col-12 password">
                        <label for="pwd"> <?= i::__('Senha'); ?> </label>
                        <input autocomplete="off" id="pwd" type="password" name="password" v-model="password" />
                        <span class="password-rules">
                            <strong><?= i::__('A senha deve ter:') ?></strong>
                            {{passwordRules.minimumPasswordLength}}<?= i::__(' caracteres, um número, um caractere especial (! @ # $ & *), pelo menos uma letra maiúscula e uma minúscula.') ?>
                        </span>
                    </div>
                    <div class="field col-12 password">
                        <label for="pwd-check">
                            <?= i::__('Confirmar senha'); ?>
                        </label>
                        <input autocomplete="off" id="pwd-check" type="password" name="confirm_password" v-model="confirmPassword" />
                    </div>                    
                    <div class="progressbar col-12">
                        <span> <?= i::__('Força da senha'); ?> </span>
                        <progress id="progress" :class="strongnessClass" :value="passwordStrongness" max="100"></progress>
                        <span id="progresslabel">{{passwordStrongness}}%</span>
                    </div>
                    <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>
                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Continuar') ?> </button>
                </form>
                
                <div v-if="configs.strategies.Google?.visible || configs.strategies.govbr?.visible" class="divider col-12"></div>

                <div class="social-login col-12">
                    <a v-if="configs.strategies.govbr?.visible" class="social-login--button button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>">                                
                        <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
                        <?= i::__('Entrar com Gov.br') ?>                            
                    </a>                    
                    <a v-if="configs.strategies.Google?.visible" class="social-login--button button button--icon button--large button--md google" href="<?php echo $app->createUrl('auth', 'google') ?>">                                
                        <div class="img"> <img height="16" src="<?php $this->asset('img/g.png'); ?>" /> </div>                                
                        <?= i::__('Entrar com Google') ?>
                    </a>
                </div>
            </div>

            <!-- Terms steps -->
            <div v-show="step==index+2" v-for="(value, name, index) in terms" class="create-account__step grid-12">
                <label class="title col-12"> {{value.title}} </label>
                <div class="term col-12" v-html="value.text" :id="'term'+index" ref="terms"></div>
                <div class="divider col-12"></div>                
                <button class="col-12 button button--primary button--large button--md disabled" :id="'acceptTerm'+index" @click="nextStep(); acceptTerm(name)"> {{value.buttonText}} </button>
                <button class="col-12 button button--text" @click="cancel()"> <?= i::__('Voltar e excluir minhas informações') ?> </button>
            </div>

            <!-- Last step -->
            <div v-if="step==totalSteps" class="create-account__step grid-12">
                <label class="title col-12">
                    <?= i::__('Criação de Agente') ?>
                    <div class="subtitle col-12">
                        <span> <?= i::__('Falta pouco para finalizar o seu cadastro!') ?> </span>
                        <span> <?= i::__('Dê um nome e faça uma breve descrição do seu Agente.') ?> </span>
                    </div>
                </label>
                
                <entity-field :entity="agent" classes="col-12" hide-required label=<?php i::esc_attr_e("Nome")?> prop="name" fieldDescription="<?= i::__('As pessoas irão encontrar você por esse nome.') ?>"></entity-field>
                <entity-field :entity="agent" classes="col-12" hide-required prop="shortDescription" label="<?php i::esc_attr_e("Descrição")?>"></entity-field>
                <entity-terms :entity="agent" classes="col-12" :editable="true" taxonomy='area' title="<?php i::esc_attr_e("Selecione pelo menos uma área de atuação") ?>"></entity-terms>                

                <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" @render="expiredCaptcha" @render="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>

                <button class="col-12 button button--primary button--large button--md" @click="register()"> <?= i::__('Criar conta') ?></button>
            </div>
        </template>
    </mapas-card>
    
    <!-- Account created -->            
    <mapas-card v-if="created" class="no-title card-created">
        <template #content>
            <div class="create-account__created grid-12">
                <div class="col-12 title">
                    <mc-icon name="circle-checked" class="title__icon"></mc-icon>
                    <label v-if="emailSent" class="col-12 title__label"> <?= i::__('E-mail de confirmação enviado!') ?> </label>
                    <label v-if="!emailSent" class="col-12 title__label"> <?= i::__('Sua conta foi criada com sucesso!') ?> </label>
                </div>

                <p v-if="emailSent" class="emailSent col-12"> <?= i::__('Acesse seu e-mail de cadastro para confirmar a criação da sua conta no Mapas Culturais.') ?> </p>

                <a href="<?= $app->createUrl('auth') ?>" class="col-12 button button--large button--primary"> <?php i::_e('Entrar na minha conta') ?> </a>
            </div>
        </template>
    </mapas-card>
</div>
