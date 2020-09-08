<?php

$this->jsObject['labels']['multiplelocal'] = $jsLabelsInternationalization;

function showStrategy($name, $config) {

    
    
    if (!isset($config['strategies'])) {
        return false;
    }

    if (!isset($config['strategies'][$name])) {
        return false;
    }

    if (isset($config['strategies'][$name]['visible'])) {
        if(!$config['strategies'][$name]['visible'] || $config['strategies'][$name]['visible'] === 'false') {
            return false;
        } else {
            return true;
        }
    }

    return false;
}
?>

<?php if (isset($config['google-recaptcha-sitekey'])) : ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<div class="login-area">
    <h6 class="text-center introduction">
        <?php \MapasCulturais\i::_e('Boas vindas!', 'multipleLocal'); ?>
    </h6>

    <?php if ($feedback_msg) : ?>
        <div class="alerta <?php echo $feedback_success ? 'sucesso' : 'erro'; ?>">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <?php echo htmlentities($feedback_msg); ?>
        </div>
    <?php endif; ?>

    <div class="section-login-wrapper">
        <div class="section-login">
            <div class="options">
                <div id="multiple-login">
                    <h5><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h5>
                    <h6 class="text-center introduction">
                        <?php \MapasCulturais\i::_e('Se você já possui uma conta. Comece pelo login. Caso tenha esquecido a senha é só clicar em "recuperar senha"', 'multipleLocal'); ?>
                    </h6>
                    <div class="login-options">
                        <form action="<?php echo $login_form_action; ?>" method="POST">
                            <input type="hidden" name="redirectUrl" value="<?php echo isset($redirectUrl) ? $redirectUrl : ''; ?>" />

                            <fieldset>
                                <label for="email">
                                    <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?>
                                    <!-- somente mostre o CPF se tiver ativado nas config -->
                                    <?php if (isset($config['enableLoginByCPF']) && $config['enableLoginByCPF']) { ?>
                                        <?php \MapasCulturais\i::_e('ou CPF', 'multipleLocal'); ?>
                                    <?php } ?>
                                </label>
                                <input type="text" name="email" id="email" value="<?php echo htmlentities($triedEmail); ?>" />
                            </fieldset>

                            <fieldset>
                                <label for="password">
                                    <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                                </label>
                                <input type="password" id="password" name="password" value="" />
                            </fieldset>

                            <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                                <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                            <?php endif; ?>

                            <div class="submit-options">
                                <a id="multiple-login-recover" class="multiple-recover-link"><?php \MapasCulturais\i::_e('esqueci a senha', 'multipleLocal'); ?></a>
                                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Entrar', 'multipleLocal'); ?>" />
                            </div>
                        </form>

                        <?php if (showStrategy('Facebook', $config) || showStrategy('Google', $config) || showStrategy('LinkedIn', $config) || showStrategy('LoginCidadao', $config)) : ?>
                            <div class="social-login">
                                <div class="social-login--title">
                                    <?php \MapasCulturais\i::_e('Ou conecte usando sua conta em', 'multipleLocal'); ?>
                                </div>

                                <div class="social-login--content">
                                    <?php if (showStrategy('Facebook', $config)) : ?>
                                        <a class="facebook" href="<?php echo $app->createUrl('auth', 'facebook') ?>">
                                            <img src="<?php $this->asset('img/fb.png'); ?>" />
                                            Facebook
                                        </a>
                                    <?php endif; ?>
                                    <?php if (showStrategy('Google', $config)) : ?>
                                        <a class="google" href="<?php echo $app->createUrl('auth', 'google') ?>">
                                            <img src="<?php $this->asset('img/g.webp'); ?>" />
                                            Google
                                        </a>
                                    <?php endif; ?>
                                    <?php if (showStrategy('LinkedIn', $config)) : ?>
                                        <a class="linkedin" href="<?php echo $app->createUrl('auth', 'linkedin') ?>">
                                            <img src="<?php $this->asset('img/in.png'); ?>" />
                                            LinkedIn
                                        </a>
                                    <?php endif; ?>
                                    <?php if (showStrategy('LoginCidadao', $config)) : ?>
                                        <a href="<?php echo $app->createUrl('auth', 'logincidadao') ?>">
                                            <img src="<?php $this->asset('img/lc-login.png'); ?>" />
                                            Login Cidadão
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php $app->applyHook('multipleLocalAuth.loginPage:end'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="account-link">
                            Ainda não possui uma conta?
                            <button>
                                Crie uma conta agora
                            </button>
                        </div>

                    </div>

                </div>

                <div id="multiple-recover" style="display:none;">
                    <h5><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></h5>
                    <div class="recover-options">
                        <form autocomplete="off" action="<?php echo $recover_form_action; ?>" method="POST">
                            <!-- <p><?php \MapasCulturais\i::_e('Para recuperar sua senha, informe o e-mail utilizado no cadastro.', 'multipleLocal'); ?></p> -->

                            <fieldset>
                                <label for="re-email">
                                    <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?>
                                </label>

                                <input autocomplete="off" type="text" id="re-email" name="email" value="" />
                            </fieldset>


                            <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                                <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                            <?php endif; ?>

                            <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar senha', 'multipleLocal'); ?>" />
                            <a id="multiple-login-recover-cancel" class="multiple-recover-link secondary"><?php \MapasCulturais\i::_e('Cancelar', 'multipleLocal'); ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="section-register">
            <h5><?php \MapasCulturais\i::_e('Criar conta', 'multipleLocal'); ?></h5>
            <h6 class="text-center introduction">
                <?php \MapasCulturais\i::_e('Preencha os campos abaixo e confirme sua conta por email para ter acesso à solicitação dos benefícios.', 'multipleLocal'); ?>
            </h6>

            <div class="register-options">
                <form autocomplete="off" action="<?php echo $register_form_action; ?>" method="POST">
                    <fieldset>
                        <label for="in-name">
                            <?php \MapasCulturais\i::_e('Nome', 'multipleLocal'); ?>
                        </label>

                        <input autocomplete="off" type="text" id="in-name" name="name" value="<?php echo htmlentities($triedName); ?>" />
                    </fieldset>

                    <fieldset>
                        <label for="in-email">
                            <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?>
                        </label>
                        <input autocomplete="off" id="in-email" type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
                    </fieldset>


                    <!-- somente mostre o CPF se tiver ativado nas config -->
                    <?php if (isset($config['enableLoginByCPF']) && $config['enableLoginByCPF']) { ?>
                        <fieldset>
                            <label for="RegraValida">
                                <?php \MapasCulturais\i::_e('CPF', 'multipleLocal'); ?>
                            </label>

                            <input autocomplete="off" type="text" id="RegraValida" value="" name="cpf" maxlength="14">
                        </fieldset>
                    <?php } ?>

                    <fieldset>
                        <label for="pwd-progress-bar-validation">
                            <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                            <div class="hoverable-options">
                                <i> ? </i>
                                <ul id="passwordRulesUL">
                                    <div class="arrow"></div>
                                </ul>
                            </div>

                        </label>
                        <input autocomplete="off" id="pwd-progress-bar-validation" type="password" name="password" value="" />
                    </fieldset>



                    <div class="progressbar">
                        <span> <?php \MapasCulturais\i::_e('Força da senha', 'multipleLocal'); ?> </span>
                        <progress id="progress" value="0" max="100">70</progress>
                        <span id="progresslabel"></span>
                    </div>

                    <fieldset>
                        <label for="in-repassword">
                            <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
                        </label>

                        <input autocomplete="off" id="in-repassword" type="password" name="confirm_password" value="" />
                    </fieldset>

                    <div class="registro__container__form__field" name="terminos" style="min-height: 0px;">
                        <div class="render-field checkbox-field">
                            <p>
                                <input onchange="this.setCustomValidity(validity.valueMissing ? 'Please indicate that you accept the Terms and Conditions' : '');" id="field_terms" type="checkbox" required name="terms">
                                <label class="caption" for="field_terms">
                                    <span> <?php \MapasCulturais\i::_e('Aceito a', 'multipleLocal'); ?>


                                        <a aria-current="false" target="_blank" 
                                            href="<?php 
                                                echo isset($config['urlTermsOfUse']) && $config['urlTermsOfUse'] != '' ? 
                                                    $config['urlTermsOfUse'] : 
                                                    env('LINK_TERMOS', $app->createUrl('auth', '', array('termos-e-condicoes')))
                                            ?>
                                        "> 
                                        <?php \MapasCulturais\i::_e('Politica de Privacidade e termos de condições de uso', 'multipleLocal'); ?></a>
                                        <?php \MapasCulturais\i::_e('do MapasCulturais', 'multipleLocal'); ?>
                                    </span>
                                </label>
                            </p>

                        </div>

                    </div>

                    <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                    <?php endif; ?>

                    <div class="submit-options">
                        <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Cadastrar', 'multipleLocal'); ?>" />
                    </div>
                </form>
            </div>

            <script type="text/javascript">
                document.getElementById("field_terms").setCustomValidity("Por favor, indique que aceita os Termos e condições de uso");
            </script>
        </div>

    </div>
</div>
