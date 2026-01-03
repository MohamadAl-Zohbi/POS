<?php
$getDate = $db->prepare("SELECT date,endOfDay FROM data");
$date;
$endOfDay;
if ($getDate->execute()) {
    $getDate = $getDate->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDate)) {
        $date = $getDate[0]['date'];
        $endOfDay = $getDate[0]['endOfDay'];
    }
}

if (date("Y-m-d") > date("$date")) {
    if ($endOfDay == "auto") {
        $sql = "UPDATE data SET date = :date WHERE 1 = 1";
        $closeDayAuto = $db->prepare($sql);
        $date = date("Y-m-d");
        $closeDayAuto->bindParam(':date', $date);
        $closeDayAuto->execute();
    } else {
        echo "<div style='position:fixed;width:100%;bottom:0;text-align:center;' class='alert alert-danger' role='alert'>الرجاء اغلاق اليوم في حال الانتهاء من جميع الاعمال</div>`";
    }
}
?>