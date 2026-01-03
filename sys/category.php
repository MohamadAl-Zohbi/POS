<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';
if (isset($_POST['add_category'])) {
    $name      = $_POST['name'];
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $addCategory = $db->prepare($sql);
    $addCategory->bindParam(':name', $name);
    if ($addCategory->execute()) {
        header('Location: category.php');
    }
}
if (isset($_GET['delete'])) {
    $name = $_GET['delete'];
    $validate = $db->prepare("SELECT * FROM products WHERE category=:name");
    $validate->bindParam(':name', $name);
    if ($validate->execute()) {
        $validate = $validate->fetchAll(PDO::FETCH_ASSOC);
        if (count($validate) == 0) {
            $deleting = $db->prepare("DELETE FROM categories WHERE name=:name");
            $deleting->bindParam(':name', $name);
            if ($deleting->execute()) header('Location: category.php');
        } else {
            echo '<script>alert("لا يمكن حذف هذه الفئة لانها قيد الاستخدام")</script>';
        }
    }
}
if (isset($_POST['editCategory'])) {
    $name = $_POST['name'];
    $sql = "UPDATE categories SET name=:name WHERE name = :name";
    $editCategory = $db->prepare($sql);
    $editCategory->bindParam(':name', $name);
    if ($editCategory->execute()) {
        header('Location: category.php');
    }
}
$categories = $db->prepare("SELECT * FROM categories");
$categoriesSelect = [];
if ($categories->execute()) {
    while ($rowe = $categories->fetch(\PDO::FETCH_BOTH)) {
        array_push($categoriesSelect, $rowe);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الفئة</title>
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">
    <link href="../common/bootstrap.css" rel="stylesheet">
    <style>
        #card {
            position: fixed;
            margin: auto;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php include_once "./navbar.php" ?>
    <div class="container">
        <br>
        <form method="POST" class="mb-4 row g-2">
            <div class="col-md-2"><input type="text" name="name" placeholder="الاسم" class="form-control" required></div>
            <div class="col-md-2"><button type="submit" name="add_category" class="btn btn-primary w-100">Add Category</button></div>
        </form>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>Name/الاسم</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($categoriesSelect as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["name"] . '</td>';
                    echo '<td><a href="?delete=' . $item['name'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">حذف</a> &nbsp;';
                    echo '<a data-name="' . $item["name"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="showDataBeforeEdit(this)">تعديل</a></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <form method="GET">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="editProductModalLabel">تعديل الفئة</h5>
                    </div>
                    <div class="mb-3" style="text-align: center;">
                        <label for="productName" class="form-label">اسم الفئة</label>
                        <input style="max-width: 500px; margin:auto;text-align:center;" type="text" class="form-control" id="productName" name="productName" placeholder="الاسم">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editCategory" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../common/bootstrap.js"></script>
<script>
    function showDataBeforeEdit(e) {
        let productName = document.getElementById("productName");
        productName.value = e.dataset.name
    }
    let urlParams = new URLSearchParams(window.location.search);
    onload = () => {
        if (urlParams.has("error")) {
            alert(
                "<?php
                    if (isset($_GET['error'])) {
                        echo $_GET['error'];
                    }
                    ?>"
            )
        }
    }
</script>
</html>