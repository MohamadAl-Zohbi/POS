<?php
include_once "../common/connect.php";
$sql = "UPDATE data SET last_run_time = null WHERE 1 = 1";
$updateDate = $db->prepare($sql);
if ($updateDate->execute()) {
    echo "date updated";
}
