<?php
define('APP_INIT', true);
require_once __DIR__ . '/../common/security.php';
require_once __DIR__ . '/../common/functions.php';

$getData = $db->prepare("SELECT * FROM data");
$data;
if ($getData->execute()) {
    $getData = $getData->fetchAll(PDO::FETCH_ASSOC);
    if (count($getData)) {
        $data = $getData[0];
    }
}

if (date("Y-m-d") >= date($data['end_date'])) {
    header('Location: ./endDate.php');
}

if (decryptString($data['uuid']) != getMacAddress()) {
    exit('this is not for this machine');
}

checkLicense();
checkTimeLock($db);



