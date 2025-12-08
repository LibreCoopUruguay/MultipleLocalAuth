<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mc-card
');
?>

<div class="confirm-email">
    <mc-card class="no-title">
        <template #content>
            <div class="grid-12">
                <div class="col-12 header">
                    <label class="header__title"> <?= i::__('Cambio de contraseña') ?> </label>
                    <mc-icon name="circle-checked" class="header__icon"></mc-icon>
                    <div class="login__header">
                        <label class="header__label"> <?= i::__('Enviamos las instrucciones de cambio de contraseña a su correo electrónico. Por las dudas verifique la carpeta SPAM.') ?> </label>
                    </div>
                </div>

                <a class="col-12 button button--primary button--large button--md" href="<?= $app->createUrl('auth') ?>" type="submit"> <?= i::__('Entrar a mi cuenta') ?> </a>
            </div>
        </template>
    </mc-card>
</div>