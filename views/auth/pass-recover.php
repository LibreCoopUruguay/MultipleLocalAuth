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

<h2><?php \MapasCulturais\i::_e('Recuperar senha', 'multipleLocal'); ?></h2>

<?php if($feedback_msg): ?>

    <?php if($feedback_msg): ?>
    <div class="alert <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        <?php echo htmlentities($feedback_msg); ?>
    </div>
    <?php endif; ?>

<?php endif; ?>

<form action="<?php echo $form_action; ?>" method="POST">

    
    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
    <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
    <input id="pwd-progress-bar-validation" type="password" name="password" value="" />
    <br/>
    <small><?php \MapasCulturais\i::_e('Medidor de força da senha', 'multipleLocal'); ?></small><br>
    <ul id="passwordRulesUL"> </ul>
    <progress id="progress" value="0" max="100">70</progress>
    <span id="progresslabel"></span>
    <br/><br/>
    <br/><br/>
    <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
    <input type="password" name="confirm_password" value="" />

    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar', 'multipleLocal'); ?>" />

</form>

