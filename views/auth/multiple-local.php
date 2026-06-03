<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

// Check if we're in MFA mode
$mode = $mode ?? 'login';

if (trim($_GET['t'] ?? '')) {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['token'] = $_GET['t']; 
}

if ($mode === 'mfa') {
    // MFA verification mode
    $this->import('mfa-verify');
    ?>
    <mfa-verify></mfa-verify>
    <?php
} else {
    // Normal login mode
    $this->import('login');
    ?>
    <login config='<?= $configs; ?>' ></login>
    <?php
}
?>