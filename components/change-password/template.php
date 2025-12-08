<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mc-modal
');
?>
<div class="change-password">
    <div v-if="myAccount" class="mfa-section-wrapper" style="margin-bottom: 24px;">
        <label class="change-password__title" style="margin-bottom: 8px; display: block;"><?= i::__('Seguridad Extra') ?></label>
        <div class="field col-12 mfa-section" style="padding: 16px; background: #fff; border: 1px solid #eee; border-radius: 4px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="flex-grow: 1;">
                    <span style="font-weight: 500; font-size: 14px; display: block;"><?= i::__('Autenticación de Dos Factores (MFA)') ?></span>
                    <span style="font-size: 13px; color: #28a745; font-weight: 600;"><?= i::__('Activa y Obligatoria') ?></span>
                </div>
            </div>
            <p class="help-text" style="font-size: 13px; color: #666; margin: 8px 0 0 0; line-height: 1.4;">
                <?= i::__('Por políticas de seguridad, el uso de MFA es obligatorio para todas las cuentas. Se le enviará un código a su email al iniciar sesión.') ?>
            </p>
        </div>
    </div>
    <label class="change-password__title"> <?= i::__('Senha:') ?> </label>

    <div class="change-password__password">
        <div class="change-password__password--fakePassword">
            <div v-for="n in 12" class="dot"></div>
        </div>

        <mc-modal title="<?= i::esc_attr__('Alteração de senha') ?>" classes="change-password__modal">
            <template #default>
                <form class="grid-12" @submit.prevent="changePassword(modal);">



                    <div v-if="myAccount" class="field col-12 password">
                        <label for="currentPassword"> <?= i::__('Senha atual'); ?> </label>
                        <input autocomplete="off" id="currentPassword" type="password" name="currentPassword" v-model="currentPassword" />
                        <div class="seePassword" @click="togglePassword('currentPassword', $event)"></div>
                    </div>

                    <div class="field col-12 password">
                        <label for="newPassword"> <?= i::__('Senha'); ?> </label>
                        <input autocomplete="off" id="newPassword" type="password" name="newPassword" v-model="newPassword" />
                        <div class="seePassword" @click="togglePassword('newPassword', $event)"></div>
                        <span class="password-rules">
                            <?= i::__('A senha deve ter:') ?>
                            <strong> {{passwordRules.minimumPasswordLength}}<?= i::__(' caracteres, um número, um caractere especial (! @ # $ & *), pelo menos uma letra maiúscula e uma minúscula.') ?></strong>
                        </span>
                    </div>

                    <div class="field col-12 password">
                        <label for="confirmNewPassword"> <?= i::__('Confirme a senha'); ?> </label>
                        <input autocomplete="off" id="confirmNewPassword" type="password" name="confirmNewPassword" v-model="confirmNewPassword" />
                        <div class="seePassword" @click="togglePassword('confirmNewPassword', $event)"></div>
                    </div>
                </form>                

            </template>

            <template #button="modal">
                <a class="change-password__password--edit" @click="modal.open()">
                    <mc-icon name="edit"></mc-icon> 
                    <label class="label"> <?= i::__('Alterar senha') ?> </label>
                </a>
            </template>     

            <template #actions="modal">
                <button class="button button--primary" @click="changePassword(modal)"> <?= i::__('Alterar senha') ?> </button>
                <button class="button button--text button--text-del" @click="cancel(modal)"><?= i::__("Cancelar") ?></button>
            </template>
        </mc-modal>
    </div>
</div>