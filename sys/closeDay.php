<?php
// include_once '../common/connect.php';
// include_once './check.php';
// include_once './onlyAdmin.php';

$getDate = $db->prepare("SELECT date FROM data");
$date;
if ($getDate->execute()) {
    $getDate = $getDate->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDate)) {
        $date = $getDate[0]['date'];
    }
}

if (date("Y-m-d") > date("$date")) {
    echo "<div style='position:fixed;width:100%;bottom:0;text-align:center;' class='alert alert-danger' role='alert'>الرجاء اغلاق اليوم في حال الانتهاء من جميع الاعمال</div>`";
}
?>