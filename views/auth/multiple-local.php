<?php

    function showStrategy($name, $config){
        if (!isset($config['strategies'])){
            return false;
        }

        if (!isset($config['strategies'][$name])){
            return false;
        }

        //Default Visible TRUE
        if (!isset($config['strategies'][$name]['visible'])){
            return true;
        } 

        return $config['strategies'][$name]['visible'] === true;
    }
?>

<?php if (isset($config['google-recaptcha-sitekey'])): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<br/>
<div style="padding-left: 5%;">


<br/>
<div class="section-login">
    <?php if($feedback_msg): ?>
    <div class="alert <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        <?php echo htmlentities($feedback_msg); ?>
    </div>
    <?php endif; ?>

    <div class="box-registro col-6" style="width:30%;">
        <div id="multiple-login">
            <h5 class="textcenter"><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h5>
            <form action="<?php echo $login_form_action; ?>" method="POST">
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?> 

                <!-- somente mostre o CPF se tiver ativado nas config -->
                <?php if(isset($config['enableLoginByCPF']) && $config['enableLoginByCPF']) { ?>
                    ou CPF
                <?php } ?>

                <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                <input type="password" name="password" value="" />
                <br/><br/>
                <?php if (isset($config['google-recaptcha-sitekey'])): ?>
                    <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
                <?php endif; ?>
                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Entrar', 'multipleLocal'); ?>" />
                <a id="multiple-login-recover" class="multiple-recover-link"><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></a>
            </form>
        </div>
        
        <div id="multiple-recover" style="display:none;">
            <h5 class="textcenter"><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></h5>
            <form action="<?php echo $recover_form_action; ?>" method="POST">
                <p><?php \MapasCulturais\i::_e('Para recuperar sua senha, informe o e-mail utilizado no cadastro.', 'multipleLocal'); ?></p>
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                <input type="text" name="email" value="" />
                <br/><br/>
                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar senha', 'multipleLocal'); ?>" />
                <a id="multiple-login-recover-cancel"  class="multiple-recover-link"><?php \MapasCulturais\i::_e('Cancelar', 'multipleLocal'); ?></a>
            </form>
        </div>
    </div>
    <?php if (showStrategy('Facebook', $config) || showStrategy('Google', $config) || showStrategy('LinkedIn', $config) || showStrategy('LoginCidadao', $config)): ?>
    <div class="box-registro  col" style="width:30%;">
        <h5 class="textcenter"><?php \MapasCulturais\i::_e('Conectar-se', 'multipleLocal'); ?></h5>
        <p><?php \MapasCulturais\i::_e('Utilize sua conta em outros serviços para entrar', 'multipleLocal'); ?>:</p>
        <p style="text-align: center;">
            <?php if (showStrategy('Facebook', $config)): ?>
            <a href="<?php echo $app->createUrl('auth', 'facebook') ?>"><img src="<?php $this->asset('img/fb-login.png'); ?>" /></a>&nbsp;&nbsp;
            <?php endif; ?>
            <?php if (showStrategy('Google', $config)): ?>
            <a href="<?php echo $app->createUrl('auth', 'google') ?>"><img src="<?php $this->asset('img/go-login.png'); ?>" /></a>&nbsp;&nbsp;
            <?php endif; ?>
            <?php if (showStrategy('LinkedIn', $config)): ?>
            <a href="<?php echo $app->createUrl('auth', 'linkedin') ?>"><img src="<?php $this->asset('img/ln-login.png'); ?>" /></a>&nbsp;&nbsp;
            <?php endif; ?>
            <?php if (showStrategy('LoginCidadao', $config)): ?>
            <a href="<?php echo $app->createUrl('auth', 'logincidadao') ?>"><img src="<?php $this->asset('img/lc-login.png'); ?>" /></a>
            <?php endif; ?>
        </p>
        <?php $app->applyHook('multipleLocalAuth.loginPage:end'); ?>
    </div>
    <?php endif; ?>

    
    
				  
    
</div>


<div class="box-registro col-6">
    <h5 class="textcenter"><?php \MapasCulturais\i::_e('Registrar-se', 'multipleLocal'); ?></h5>

    <form action="<?php echo $register_form_action; ?>" method="POST">        
            
        <?php \MapasCulturais\i::_e('Nome', 'multipleLocal'); ?>
        <input type="text" name="name" value="<?php echo htmlentities($triedName); ?>" />
        <br/><br/>
        <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
        <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
        <br/><br/>

        <!-- somente mostre o CPF se tiver ativado nas config -->
        <?php if(isset($config['enableLoginByCPF']) && $config['enableLoginByCPF']) { ?>
            <?php \MapasCulturais\i::_e('CPF', 'multipleLocal'); ?>
            <input type="text" id="RegraValida" value="" name="cpf" maxlength="14">
            <br/><br/>
        <?php } ?>

        
        <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
        <input id="pwd-progress-bar-validation"  type="password" name="password" value="" />
        <small>Medidor de força da senha</small><br>
        <ul id="passwordRulesUL"> </ul>
        <progress id="progress" value="0" max="100">70</progress>
        <span id="progresslabel"></span>
        <br/><br/>
        <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
        <input type="password" name="confirm_password" value="" />

        <div class="registro__container__form__field" name="terminos" style="min-height: 0px;">
		<div class="render-field checkbox-field">
			<p><input onchange="this.setCustomValidity(validity.valueMissing ? 'Please indicate that you accept the Terms and Conditions' : '');" id="field_terms" type="checkbox" required name="terms"> 
				<label class="caption" for="terminos">
					<span> Aceito a
						<a aria-current="false" target="_blank" href="<?php echo $app->createUrl('auth', '', array('termos-e-condicoes')) ?>"> Politica de Privacidade e termos de condições de uso</a> 
						do MapasCulturaisCeara
					</span>
				</label>
			</p>

		</div>
<!--
		<div id="html_element"></div>
-->
	</div>
        <?php if (isset($config['google-recaptcha-sitekey'])): ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $config['google-recaptcha-sitekey']; ?>"></div>
        <?php endif; ?>
	<br/>

	<input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Registrar-se', 'multipleLocal'); ?>" />

<!-- estas son las cosas del Leo ;)
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
</script>
-->
    </div>
</form>


<script type="text/javascript">
  document.getElementById("field_terms").setCustomValidity("Por favor, indique que aceita os Termos e condições de uso");
</script>


</div>

</div>
