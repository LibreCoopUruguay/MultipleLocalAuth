<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

if (trim($_GET['t'] ?? '')) {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['token'] = $_GET['t'];
}

if (!empty($forcePasswordChange)) {
    $this->jsObject['forcePasswordChangeMode'] = true;
    $this->jsObject['forcePasswordChangeEmail'] = $app->user->email;
}

$this->import('
    login
')
?>

<login config='<?= $configs; ?>' ></login>