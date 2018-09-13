<?php $this->layout = 'panel'; ?>

<div class="panel-list panel-main-content">
    
    <?php if($feedback_msg): ?>

        <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
            <?php echo htmlentities($feedback_msg); ?>
        </div>

    <?php endif; ?>
    
    <form action="<?php echo $form_action; ?>" method="POST">

        <h2><?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?></h2>

        
        <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?>
        <input type="text" name="email" value="<?php echo htmlentities($email); ?>" />
        <br/><br/>
       
        <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Guardar alteraçoes', 'multipleLocal'); ?>" />  

        <h2><?php \MapasCulturais\i::_e('Trocar Senha', 'multipleLocal'); ?></h2>

        <?php \MapasCulturais\i::_e('Senha atual', 'multipleLocal'); ?>
        <input type="password" name="current_pass" value="" />
        <br/><br/>
        <?php \MapasCulturais\i::_e('Nova senha', 'multipleLocal'); ?>
        <input type="password" name="new_pass" value="" />
        <br/><br/>
        <?php \MapasCulturais\i::_e('Confirmar nova senha', 'multipleLocal'); ?>
        <input type="password" name="confirm_new_pass" value="" />
<br/><br/>
        <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Guardar alteraçoes', 'multipleLocal'); ?>" />

    </form>
</div>
