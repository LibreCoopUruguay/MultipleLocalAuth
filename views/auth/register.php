<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$this->import('
    create-account
    mapas-breadcrumb
');

$this->breadcrumb = [
    ['label'=> i::__('Voltar para entrar na conta'), 'url' => $app->createUrl('auth')],
];
?>

<mapas-breadcrumb></mapas-breadcrumb>

<create-account></create-account>