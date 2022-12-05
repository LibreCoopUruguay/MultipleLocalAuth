<?php $this->layout = 'panel'; ?>

<div class="panel-list panel-main-content">
    
    <?php if($feedback_msg): ?>

        <div class="auth_feedback <?php echo $feedback_success ? 'success' : 'error'; ?>">
            <?php echo htmlentities($feedback_msg); ?>
        </div>

    <?php endif; ?>
    
    <form action="<?php echo $form_action; ?>" method="POST">
        <div class="auth-row general_field">
            <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Guardar alteraçoes', 'multipleLocal'); ?>" />  
            <h2><?php \MapasCulturais\i::_e('Trocar e-mail', 'multipleLocal'); ?></h2>

            <div>
                <label> 
                    <?php \MapasCulturais\i::_e('Email', 'multipleLocal'); ?> 
                    <input type="text" name="email" value="<?php echo htmlentities($email); ?>" />
                </label>
            </div>
            
            <div>
                <h2><?php \MapasCulturais\i::_e('Trocar Senha', 'multipleLocal'); ?></h2>
                <div>
                    <label> 
                        <?php \MapasCulturais\i::_e('Senha atual', 'multipleLocal'); ?>
                        <input type="password" name="current_pass" value="" />
                    </label>
                </div>

                <div>
                    <label> 
                        <?php \MapasCulturais\i::_e('Nova senha', 'multipleLocal'); ?>
                        <input type="password" name="new_pass" value="" />
                    </label>
                </div>
            </div>

            <div>
                <label> 
                    <?php \MapasCulturais\i::_e('Confirmar nova senha', 'multipleLocal'); ?>
                    <input type="password" name="confirm_new_pass" value="" />
                </label>
            </div>

            <div>
                <?php if($config['strategies']['govbr']['visible']): ?>
                    <?php if($has_seal_govbr):?> 
                        <h1><?= $menssagem_authenticated ?></h1>
                    <?php else:?>
                        <a class="br-sign-in" href="http://localhost/autenticacao/govbr/" style="background-color: #eee;color: black;">
                                Entrar com
                            <img class="br-sign-in-img" src="<?=$this->asset("img/gov.br_logo.svg")?>" style="margin-left: 6px;width: 23% !important;-webkit-filter: none !important;filter: none !important;color:#000;">
                        </a>
                    <?php endif ?>
                <?php endif ?>
            </div>

        </div>
        
        <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Guardar alteraçoes', 'multipleLocal'); ?>" />
        
    </form>
</div>
