<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

if (isset($_GET['e']) && $_GET['e'] != '' && isset($_GET['t']) && $_GET['t'] != '') {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['email'] = $_GET['e'];
    $this->jsObject['recoveryMode']['token'] = $_GET['t']; 
}

$this->import('
    login
')
?>

<login config='<?= $configs; ?>' ></login>