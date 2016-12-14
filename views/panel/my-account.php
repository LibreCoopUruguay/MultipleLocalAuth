<?php $this->layout = 'panel'; ?>

<div class="panel-list panel-main-content">
    
    <?php if($feedback_msg): ?>

        <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
            <?php echo $feedback_msg; ?>
        </div>

    <?php endif; ?>
    
    <form action="<?php echo $form_action; ?>" method="POST">

        <h2>Email</h2>

        
        Email
        <input type="text" name="email" value="<?php echo $email; ?>" />
        <br/><br/>
       
        <input type="submit" value="Guardar alteraçoes" />  

        <h2>Trocar Senha</h2>

        Senha atual
        <input type="password" name="current_pass" value="" />
        <br/><br/>
        Nova senha
        <input type="password" name="new_pass" value="" />
        <br/><br/>
        Confirmar nova senha
        <input type="password" name="confirm_new_pass" value="" />
<br/><br/>
        <input type="submit" value="Guardar alteraçoes" />

    </form>
</div>
