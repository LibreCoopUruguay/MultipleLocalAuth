<br/>

<?php if($feedback_msg): ?>

    <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <?php echo $feedback_msg; ?>
    </div>

<?php endif; ?>

<h2><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h2>

<form action="<?php echo $login_form_action; ?>" method="POST">

    
    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
    <input type="text" name="email" value="<?php echo $triedEmail; ?>" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
    <input type="password" name="password" value="" />

    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Entrar', 'multipleLocal'); ?>" />

</form>

<h2><?php \MapasCulturais\i::_e('Registrar-se', 'multipleLocal'); ?></h2>

<form action="<?php echo $register_form_action; ?>" method="POST">

    <?php \MapasCulturais\i::_e('Nome', 'multipleLocal'); ?>
    <input type="text" name="name" value="<?php echo $triedName; ?>" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
    <input type="text" name="email" value="<?php echo $triedEmail; ?>" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
    <input type="password" name="password" value="" />
    <br/><br/>
    <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
    <input type="password" name="confirm_password" value="" />

    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Registrar-se', 'multipleLocal'); ?>" />

</form>

<h2><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></h2>

<form action="<?php echo $recover_form_action; ?>" method="POST">

    <p><?php \MapasCulturais\i::_e('Para recuperar sua senha, informe o e-mail utilizado no cadastro.', 'multipleLocal'); ?></p>
    <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
    <input type="text" name="email" value="" />
    <br/><br/>

    <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar senha', 'multipleLocal'); ?>" />

</form>


<h2><?php \MapasCulturais\i::_e('Redes Sociais', 'multipleLocal'); ?></h2>


<p><?php \MapasCulturais\i::_e('Utilize sua conta em outros serviços para autenticar-se', 'multipleLocal'); ?>:</p>
<a href="<?php echo $app->createUrl('auth', 'facebook') ?>"><img src="<?php $this->asset('img/fb-login.png'); ?>" /></a>&nbsp;&nbsp;
<a href="<?php echo $app->createUrl('auth', 'google') ?>"><img src="<?php $this->asset('img/go-login.png'); ?>" /></a>&nbsp;&nbsp;
<a href="<?php echo $app->createUrl('auth', 'linkedin') ?>"><img src="<?php $this->asset('img/ln-login.png'); ?>" /></a>
<!--<a href="<?php echo $app->createUrl('auth', 'twitter') ?>">Twitter</a> -->

<?php $app->applyHook('multipleLocalAuth.loginPage:end'); ?>


<br/>
