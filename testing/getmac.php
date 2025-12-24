<?php
define('APP_INIT', true);
require_once __DIR__ . '/../common/security.php';
require_once __DIR__ . '/../common/functions.php';


$mac = getMacAddress();
echo $mac ? $mac : 'MAC not found';
// echo encryptString("50-7B-9D-46-FB-9D");
// echo decryptString("FDxY0Hx8KwMK2TgRu8KxwG9cdghoMXZ3XSqEZfgt3i4=");
?>
