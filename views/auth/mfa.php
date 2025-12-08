<?php
$this->import('
    mc-card
    mc-icon
    mfa-verify
');
?>

<div class="mfa-auth">
    <div class="login__action">
        <div class="login__card">
            <div class="login__card__header">
                <h3> <?= $this->text('mfa_title', i::__('Verificación de Identidad')) ?> </h3>
                <h6> <?= sprintf($this->text('mfa_desc', i::__('Hemos enviado un código a su correo electrónico.'))) ?> </h6>
            </div>
            <div class="login__card__content">
                <mfa-verify></mfa-verify>
            </div>
        </div>
    </div>
</div>
