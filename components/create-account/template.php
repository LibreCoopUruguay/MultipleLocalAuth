<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mapas-card
    mc-icon
');
?>

<div class="register">
    
    <div class="register__title">
        <label> <?= i::__('Criar uma conta') ?> </label>
        <p> <?= i::__('Siga os passos para criar o seu cadastro no Mapa da Cultura Brasileira.') ?> </p>
    </div>

    <mapas-card>
        <template #title>            
            <div class="register__timeline">
                <span class="step active">
                    <div class="count">1</div>
                </span>
                <span class="step active">
                    <div class="count">2</div>
                </span>
                <span class="step active">
                    <div class="count">3</div>
                </span>
                <span class="step">
                    <div class="count">4</div>
                </span>
                <span class="step">
                    <div class="count">5</div>
                </span>
            </div>
        </template>

        <template #content>

            <div class="register__step grid-12">
                <label class="title col-12"><?= i::__('Crie sua conta no Mapas') ?></label>

                <div class="field col-12">
                    <label for="email"> <?= i::__('E-mail') ?> </label>
                    <input type="text" name="email" id="email" value="" />
                </div>

                <div class="field col-12">
                    <label for="cpf"> <?= i::__('CPF') ?> </label>
                    <input type="text" name="cpf" id="cpf" value="" />
                </div>

                <div class="field col-12 password">
                    <label class="password-label" for="pwd-progress-bar-validation">
                        <?= i::__('Senha'); ?>
                        <div class="question">
                            <mc-icon name="question"></mc-icon>
                        </div>
                    </label>
                    <input autocomplete="off" id="pwd-progress-bar-validation" type="password" name="password" value="" />
                    <span class="password-rules">
                        <strong><?= i::__('A senha deve ter:') ?></strong>
                        <?= i::__('8 caracteres, um número, um caractere especial (! @ # $ & *), pelo menos uma letra maiúscula e uma minúscula.') ?>
                    </span>
                </div>

                <div class="field col-12">
                    <label for="in-repassword">
                        <?= i::__('Confirmar senha'); ?>
                    </label>
                    <input autocomplete="off" id="in-repassword" type="password" name="confirm_password" value="" />
                </div>

                <div class="progressbar col-12">
                    <span> <?= i::__('Força da senha'); ?> </span>
                    <progress id="progress" value="0" max="100"></progress>
                    <span id="progresslabel"></span>
                </div>

                <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Continuar') ?> </button>

                <div class="divider col-12"></div>

            </div>

        </template>
    </mapas-card>
</div>
