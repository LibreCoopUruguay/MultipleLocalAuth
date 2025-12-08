<?php use MapasCulturais\i; ?>
<div class="login">
    <div class="login__action">
        <div class="login__card">
            <div class="login__card__header">
                <h3><?= i::__('Verificación de Seguridad') ?></h3>
                <h6><?= i::__('Ingrese el código de 6 dígitos que enviamos a su correo electrónico') ?></h6>
            </div>

            <div class="login__card__content">
                <form class="login__form" @submit.prevent="verify()">
                    <div class="login__fields">
                        <div class="field">
                            <label for="mfa-code"><?= i::__('Código de Verificación') ?></label>
                            <input 
                                type="text" 
                                id="mfa-code" 
                                v-model="code" 
                                placeholder="000000" 
                                maxlength="6" 
                                autocomplete="off"
                                style="text-align: center; font-size: 24px; letter-spacing: 8px; font-weight: bold;"
                            />
                        </div>

                        <div v-if="error" class="alert error" style="margin-top: 15px;">
                            {{ error }}
                        </div>

                        <div v-if="success" class="alert success" style="margin-top: 15px;">
                            {{ success }}
                        </div>
                    </div>

                    <div class="login__buttons">
                        <button 
                            class="button button--primary button--large button--md" 
                            type="submit" 
                            :disabled="isLoading || code.length < 6"
                        >
                            <span v-if="isLoading"><?= i::__('Verificando...') ?></span>
                            <span v-else><?= i::__('Verificar') ?></span>
                        </button>
                    </div>
                </form>

                <div class="login__footer" style="margin-top: 20px; text-align: center;">
                    <a href="#" @click.prevent="resend()" :class="{'disabled': isLoading}">
                        <?= i::__('¿No recibiste el código? Reenviar') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
