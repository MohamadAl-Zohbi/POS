<?php
include_once '../common/connect.php';
include_once './check.php';

$result = $db->prepare("SELECT * FROM users WHERE username = :username");
$result->bindParam(':username', $_SESSION['username']);

if ($result->execute()) {
    $result = $result->fetch(PDO::FETCH_OBJ);
    if (isset($result->username)) {
        if ($result->role != "admin") {
            header('Location: ../notAllowed.php');
        }
    }
} else {
    header('Location: ../Erorr.php');
}

// 122
?>
