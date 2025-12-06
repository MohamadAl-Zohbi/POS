<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>ddd</h1>
    <?php
    include_once '../common/connect.php';
    include_once './check.php';
    include_once './onlyAdmin.php';


    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {

        $fileName = time() . "_" . $_FILES['logo']['name']; // unique name
        $targetPath = "../uploads/" . $fileName;

        // Move file
        move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath);

        // Save into DB
        $company_name = $_POST['company_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];




        $sql = "UPDATE data SET logo = :logo, tel = :tel, address = :address, company_name = :company_name WHERE 1=1";
        $editData = $db->prepare($sql);
        $editData->bindParam(':logo', $fileName);
        $editData->bindParam(':tel', $tel);
        $editData->bindParam(':address', $address);
        $editData->bindParam(':company_name', $company_name);

        if ($editData->execute()) {
            // header('Location: ./products.php');
        }
        echo "Saved!";
    }
    ?>
</body>

</html>