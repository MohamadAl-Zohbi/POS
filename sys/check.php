<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
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


