<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$strategies = json_encode($config['strategies']);

$this->import('
    create-account
    mapas-breadcrumb
');

$this->breadcrumb = [
    ['label'=> i::__('Voltar para entrar na conta'), 'url' => $app->createUrl('auth')],
];
?>

<mapas-breadcrumb></mapas-breadcrumb>

<create-account config='<?= $strategies; ?>'></create-account>