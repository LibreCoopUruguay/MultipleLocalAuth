<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$this->import('
    mapas-breadcrumb
    mapas-card
    mapas-container
    mc-icon
');

$this->breadcrumb = [
    ['label'=> i::__('Voltar para entrar na conta'), 'url' => $app->createUrl('auth')],
];
?>

<mapas-breadcrumb></mapas-breadcrumb>

