<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

$this->import('
    create-account
    mapas-breadcrumb
');

$this->breadcrumb = [
    ['label'=> i::__('Voltar'), 'url' => $app->createUrl('auth')],
];
?>

<mapas-breadcrumb></mapas-breadcrumb>

<create-account config='<?= $configs; ?>'></create-account>