<?php
/** @var MapasCulturais\Theme $this */
use MapasCulturais\App;
use MapasCulturais\i;
$app = App::i();

$this->import('
    change-password
');
?>

<change-password :entity="entity"></change-password>