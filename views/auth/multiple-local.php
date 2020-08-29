<?php

$this->jsObject['labels']['multiplelocal'] = $jsLabelsInternationalization;

function showStrategy($name, $config) {
    if (!isset($config['strategies'])) {
        return false;
    }

    if (!isset($config['strategies'][$name])) {
        return false;
    }

    //Default Visible TRUE
    if (!isset($config['strategies'][$name]['visible'])) {
        return true;
    }

    return $config['strategies'][$name]['visible'] === true;
}
?>

<?php if (isset($config['google-recaptcha-sitekey'])) : ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<br />
<div class="section-login-wrapper">
    <div class="section-login">
        <?php if ($feedback_msg) : ?>
            <div class="alert <?php echo $feedback_success ? 'success' : 'error'; ?>">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?php echo htmlentities($feedback_msg); ?>
            </div>
        <?php endif; ?>

        <div class="options">
            <div id="multiple-login">
                <h5 class="textcenter"><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h5>
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
                </div>

            </div>

            <div id="multiple-recover" style="display:none;">
                <h5><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></h5>
                <form action="<?php echo $recover_form_action; ?>" method="POST">
                    <p><?php \MapasCulturais\i::_e('Para recuperar sua senha, informe o e-mail utilizado no cadastro.', 'multipleLocal'); ?></p>
                    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                    <input type="text" name="email" value="" />
                    <br /><br />

                    <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                    <?php endif; ?>

                    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar senha', 'multipleLocal'); ?>" />
                    <a id="multiple-login-recover-cancel" class="multiple-recover-link"><?php \MapasCulturais\i::_e('Cancelar', 'multipleLocal'); ?></a>
                </form>
            </div>
        </div>
    </div>


    <div class="section-register">
        <h5><?php \MapasCulturais\i::_e('Criar cadastro', 'multipleLocal'); ?></h5>

        <form action="<?php echo $register_form_action; ?>" method="POST">
            <?php \MapasCulturais\i::_e('Nome', 'multipleLocal'); ?>
            <input type="text" name="name" value="<?php echo htmlentities($triedName); ?>" />

            <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
            <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />


            <!-- somente mostre o CPF se tiver ativado nas config -->
            <?php if (isset($config['enableLoginByCPF']) && $config['enableLoginByCPF']) { ?>
                <?php \MapasCulturais\i::_e('CPF', 'multipleLocal'); ?>
                <input type="text" id="RegraValida" value="" name="cpf" maxlength="14">
            <?php } ?>


            <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
            <input id="pwd-progress-bar-validation" type="password" name="password" value="" />

            <small><?php \MapasCulturais\i::_e('Medidor de força da senha', 'multipleLocal'); ?></small>

            <ul id="passwordRulesUL"> </ul>

            <progress id="progress" value="0" max="100">70</progress>

            <span id="progresslabel"></span>

            <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
            <input type="password" name="confirm_password" value="" />

            <div class="registro__container__form__field" name="terminos" style="min-height: 0px;">
                <div class="render-field checkbox-field">
                    <p>
                        <input onchange="this.setCustomValidity(validity.valueMissing ? 'Please indicate that you accept the Terms and Conditions' : '');" id="field_terms" type="checkbox" required name="terms">
                        <label class="caption" for="terminos">
                            <span> <?php \MapasCulturais\i::_e('Aceito a', 'multipleLocal'); ?>
                                <a aria-current="false" target="_blank" href="<?php echo $app->createUrl('auth', '', array('termos-e-condicoes')) ?>"> <?php \MapasCulturais\i::_e('Politica de Privacidade e termos de condições de uso', 'multipleLocal'); ?></a>
                                <?php \MapasCulturais\i::_e('do MapasCulturais', 'multipleLocal'); ?>
                            </span>
                        </label>
                    </p>

                </div>

            </div>

            <?php if (isset($config['google-recaptcha-sitekey'])) : ?>
                <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
            <?php endif; ?>

            <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Registrar-se', 'multipleLocal'); ?>" />
        </form>


        <script type="text/javascript">
            document.getElementById("field_terms").setCustomValidity("Por favor, indique que aceita os Termos e condições de uso");
        </script>
    </div>

</div>