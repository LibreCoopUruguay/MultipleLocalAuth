<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<br/>
<div style="padding-left: 5%;">
<?php if($feedback_msg): ?>
<!--
    <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <?php echo htmlentities($feedback_msg); ?>
    </div>
-->

<div class="alert <?php echo $feedback_success ? 'success' : 'error'; ?>">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <?php echo htmlentities($feedback_msg); ?>
</div>


<?php endif; ?>

<div class="box-registro col-6">
    
    <div id="multiple-login">
    
        <h5 class="textcenter"><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h5>

            <form action="<?php echo $login_form_action; ?>" method="POST">
                
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                <input type="password" name="password" value="" />
                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Entrar', 'multipleLocal'); ?>" />
                <a id="multiple-login-recover" class="multiple-recover-link"><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></a>
                
            </form>
            
    </div>
    
    <div id="multiple-recover">
    
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
    
				<div>
						<span style="font-size: smaller;"><p> <i>Si no logras acceder a la plataforma sugerimos restablecer contraseña, haciendo clic sobre "Olvidé mi contraseña", si el problema persiste o por cualquier consulta comunicarse con: culturaenlinea@mec.gub.uy</i></p>	</span>				
				</div>    
    
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
        <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
        <input type="password" name="password" value="" />
        <br/><br/>
        <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
        <input type="password" name="confirm_password" value="" />

        <div class="registro__container__form__field" name="terminos" style="min-height: 0px;">
		<div class="render-field checkbox-field">
			<p><input onchange="this.setCustomValidity(validity.valueMissing ? 'Please indicate that you accept the Terms and Conditions' : '');" id="field_terms" type="checkbox" required name="terms"> 
				<label class="caption" for="terminos">
					<span> Acepto la
						<a aria-current="false" target="_blank" href="<?php echo $app->createUrl('site', 'page', array('terminos-y-condiciones')) ?>"> Política de Privacidad y los Términos y Condiciones</a>
						de Culturaenlinea.uy
					</span>
				</label>
			</p>

		</div>
<!--
		<div id="html_element"></div>
-->
	</div>

        <div class="g-recaptcha" data-sitekey="6LdZBNAUAAAAAGKzUKyL2UAU5Q5v9LsO2iCiDN8L"></div>
	<br/>

	<input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Registrar-se', 'multipleLocal'); ?>" />

<!-- estas son las cosas del Leo ;)
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
</script>
-->
    </div>
</form>


<script type="text/javascript">
  document.getElementById("field_terms").setCustomValidity("Por favor, indica que aceptas los Términos y Condiciones");
</script>


</div>


</div>
