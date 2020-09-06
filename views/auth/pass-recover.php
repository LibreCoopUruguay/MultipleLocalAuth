<br/>

<?php 

use MapasCulturais\i;

$jsLabelsInternationalization = [
    'passwordMustHaveCapitalLetters'=> i::__('A senha deve conter uma letra maiúscula', 'multipleLocal'),
    'passwordMustHaveLowercaseLetters'=> i::__('A senha deve conter uma letra minúscula', 'multipleLocal'),
    'passwordMustHaveSpecialCharacters'=> i::__('A senha deve conter um caractere especial', 'multipleLocal'),
    'passwordMustHaveNumbers'=> i::__('A senha deve conter um número ', 'multipleLocal'),
    'minimumPasswordLength'=> i::__('O tamanho mínimo da senha é de: ', 'multipleLocal'),
];
$this->jsObject['labels']['multiplelocal'] = $jsLabelsInternationalization;

?>

<div class="login-area recover">
    <?php if ($feedback_msg) : ?>
        <div class="alerta <?php echo $feedback_success ? 'sucesso' : 'erro'; ?>">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <?php echo htmlentities($feedback_msg); ?>
        </div>
    <?php endif; ?>

    <div class="section-login-wrapper single-item">
        <div class="section-register active">
            <h5 class="text-center"><?php \MapasCulturais\i::_e('Recuperar senha', 'multipleLocal'); ?></h5>
            <div class="register-options">
            <form action="" method="POST">
                <fieldset>
                    <label for="email">
                        <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?>
                    </label>
                    <input type="text" name="email" id="email" value="<?php echo htmlentities($triedEmail); ?>" />
                </fieldset>

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
                    <input id="pwd-progress-bar-validation" type="password" name="password" value="" />
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

                    <input id="in-repassword" type="password" name="confirm_password" value="" />
                </fieldset>

                <div class="submit-options">
                    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar', 'multipleLocal'); ?>" />
                </div>
            </form>
            </div>
        </div>
    </div>
</div>