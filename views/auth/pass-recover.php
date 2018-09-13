<br/>

<h2><?php \MapasCulturais\i::_e('Recuperar senha', 'multipleLocal'); ?></h2>

<?php if($feedback_msg): ?>

    <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <?php echo htmlentities($feedback_msg); ?>
    </div>

<?php endif; ?>

<form action="<?php echo $form_action; ?>" method="POST">

    
    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
    <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
    <input type="password" name="password" value="" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
    <input type="password" name="confirm_password" value="" />

    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar', 'multipleLocal'); ?>" />

</form>

