<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $fileName = time() . "_" . $_FILES['logo']['name'];
    $targetPath = "../uploads/" . $fileName;

    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
        die("Upload failed");
    }
    $company_name = $_POST['company_name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $endOfDay = $_POST['endOfDay'];

    $sql = "UPDATE data 
            SET logo = :logo,
                tel = :tel,
                address = :address,
                company_name = :company_name,
                endOfDay = :endOfDay
            WHERE 1 = 1";
    $editData = $db->prepare($sql);
    if ($editData->execute([
        ':logo' => $fileName,
        ':tel' => $tel,
        ':address' => $address,
        ':company_name' => $company_name,
        ':endOfDay' => $endOfDay
    ])) {
        header('Location: ./dashboard.php');
    }
} else if (isset($_POST["address"]) || isset($_POST["tel"])) {

    $company_name = $_POST['company_name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $endOfDay = $_POST['endOfDay'];

    $sql = "UPDATE data 
            SET tel = :tel,
                address = :address,
                company_name = :company_name,
                endOfDay = :endOfDay
            WHERE 1 = 1";

    $editData = $db->prepare($sql);

    if ($editData->execute([
        ':tel' => $tel,
        ':address' => $address,
        ':company_name' => $company_name,
        ':endOfDay' => $endOfDay
    ])) {
        header('Location: ./dashboard.php');
    }
}
