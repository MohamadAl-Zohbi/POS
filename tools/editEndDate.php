<?php
include_once "../common/connect.php";
if (isset($_GET['updateDate'])) {
    $sql = "UPDATE data SET end_date = :date WHERE 1 = 1";
    $updateDate = $db->prepare($sql);
    $date = $_GET['updateDate'];
    $updateDate->bindParam(':date', $date);
    if ($updateDate->execute()) {
        echo "date updated";
    }
} else {
}
?>
<script>
    function updateDollar() {
        let date = prompt("ادخل التاريخ yyyy-mm-dd");
        while (date == "") {
            date = prompt("ادخل التاريخ yyyy-mm-dd");
        }
        if (date === null) {
            return false;
        }
        let url = "?updateDate=" + date;
        window.location.href = url;
    }
    updateDollar()
</script>