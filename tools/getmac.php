<?php
define('APP_INIT', true);
require_once __DIR__ . '/../common/security.php';
require_once __DIR__ . '/../common/functions.php';
include_once "../common/connect.php";


$mac = getMacAddress();
if (!$mac) {
    echo 'MAC not found';
    exit;
}

$sql = "UPDATE data SET uuid = :uuid WHERE 1 = 1";
$updateUuid = $db->prepare($sql);
$mac = encryptString($mac);
$updateUuid->bindParam(':uuid', $mac);
if ($updateUuid->execute()) {
    echo "mac updated ";
}
echo $mac ? $mac : 'MAC not found';

// echo encryptString("50-7B-9D-46-FB-9D");
// echo decryptString("FDxY0Hx8KwMK2TgRu8KxwG9cdghoMXZ3XSqEZfgt3i4=");
