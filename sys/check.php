<?php
define('APP_INIT', true);
require_once __DIR__ . '/../common/security.php';
require_once __DIR__ . '/../common/functions.php';


session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
}

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
    header('Location: ./index.php');
}

checkLicense();
checkTimeLock($db);



