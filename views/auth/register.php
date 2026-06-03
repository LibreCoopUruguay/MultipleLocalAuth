<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

$this->import('create-account');
$this->import('mc-breadcrumb');
$this->import('password-strongness');

$this->breadcrumb = [
    ['label'=> i::__('Volver'), 'url' => $app->createUrl('auth')],
];
?>

<mc-breadcrumb></mc-breadcrumb>

<create-account config='<?= $configs; ?>'></create-account>