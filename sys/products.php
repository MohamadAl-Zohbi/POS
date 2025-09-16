<!-- <script>
     function formatNumber(number) {
            // remove any non-digit characters
            let value = JSON.stringify(number).replace(/\D/g, "");

            // format with thousands separator
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script> -->
<?php
include_once '../common/connect.php';
include_once './check.php';
include_once './onlyAdmin.php';
if (isset($_POST['add_product'])) {
    $name      = $_POST['name'];
    $barcode   = $_POST['barcode'];
    $price     = $_POST['price'];
    $costprice = $_POST['cost_price'];
    $stock_quantity  = $_POST['stock_quantity'];
    $category  = $_POST['category'];

    $sql = "INSERT INTO products (name, barcode, price, cost_price, stock_quantity, category) 
            VALUES (:name,:barcode,:price,:costprice,:stock_quantity,:category)";

    $addProduct = $db->prepare($sql);
    $addProduct->bindParam(':name', $name);
    $addProduct->bindParam(':barcode', $barcode);
    $addProduct->bindParam(':price', $price);
    $addProduct->bindParam(':costprice', $costprice);
    $addProduct->bindParam(':stock_quantity', $stock_quantity);
    $addProduct->bindParam(':category', $category);
    if ($addProduct->execute()) {
        header('Location: products.php');
    }
}




// --- Delete Product ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $validate = $db->prepare("SELECT * FROM sale_items WHERE product_id=:id");
    $validate->bindParam(':id', $id);
    if ($validate->execute()) {
        $validate = $validate->fetchAll(PDO::FETCH_ASSOC);
        if (count($validate) == 0) {
            $deleting = $db->prepare("DELETE FROM products WHERE id=:id");
            $deleting->bindParam(':id', $id);
            if ($deleting->execute()) header('Location: products.php');
        } else {
            echo '<script>alert("لا يمكن حذف هذا المنتج لانه قيد الاستخدام")</script>';
        }
    }
}


// --- Edit Product ---
// if (isset($_POST['editProduct'])) {
//     $name      = $_POST['name'];
//     $barcode   = $_POST['barcode'];
//     $price     = $_POST['price'];
//     $costprice = $_POST['cost_price'];
//     $stock_quantity  = $_POST['stock_quantity'];
//     $category  = $_POST['category'];

//     $sql = "UPDATE products SET name='Moudi', barcode='123', price='22', cost_price='12', stock_quantity='22', category=10 WHERE id = 1";

//     $addProduct = $db->prepare($sql);
//     $addProduct->bindParam(':name', $name);
//     $addProduct->bindParam(':barcode', $barcode);
//     $addProduct->bindParam(':price', $price);
//     $addProduct->bindParam(':costprice', $costprice);
//     $addProduct->bindParam(':stock_quantity', $stock_quantity);
//     $addProduct->bindParam(':category', $category);
//     if ($addProduct->execute()) {
//         header('Location: products.php');
//     }
// }


$result = $db->prepare("SELECT * FROM products");
$categories = $db->prepare("SELECT * FROM categories");

$data = [];
$categoriesSelect = [];

if ($result->execute()) {
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        array_push($data, $row);
    }
}

if ($categories->execute()) {
    while ($rowe = $categories->fetch(\PDO::FETCH_BOTH)) {
        array_push($categoriesSelect, $rowe);
    }
}

// if(isset($_GET['error'])){
// echo $_GET['error'];
// }

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Products Control</title>
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
            /* padding: 20px; */
        }


        /* 
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }

        .modal {
            background-color: white; 
            padding: 20px 30px;
            border-radius: 8px;
            max-width: 400px;
            color: red;
            z-index: 1000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .modal h2 {
            margin-bottom: 15px;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-yes {
            background-color: #3498db;
            color: white;
        }

        .btn-no {
            background-color: #e74c3c;
            color: white;
        }

        .btn-yes:hover {
            background-color: #2980b9;
        } */
    </style>
</head>

<body>
<?php include_once "./navbar.php"?>
    <!-- <div class="modal-overlay" id="dialog">
        <div class="modal">
            <h2>
                
            </h2>
            <div class="modal-buttons">
                <button class="btn-no" onclick="closeDialog()">OK</button>
            </div>
        </div>
    </div> -->

    <div class="container">
        <br>
        <!-- <a href="./dashboard.php" style="float: right; text-decoration: none;"><h4>العودة الى الصفحة الرئيسية </h4></a>

        <h2 class="mb-4">Products Control
            <div class="col-md-2"><input type="number" readonly placeholder="Dollar" step="0.01" class="form-control"></div>
        </h2> -->

        <!-- Add Product Form -->
        <form method="POST" class="mb-4 row g-2">
            <div class="col-md-2"><input type="text" name="name" placeholder="الاسم" class="form-control" required></div>
            <div class="col-md-2"><input type="text" name="barcode" placeholder="باركود" class="form-control" required></div>
            <!-- format the number -->
            <div class="col-md-1"><input id="addPrice" type="number" step="0.01" name="price" placeholder="السعر" class="form-control" required>
                <label id="addPriceLabel">

                </label>
            </div>
            <script>
                document.getElementById("addPrice").addEventListener("input", function(e) {
                    // remove any non-digit characters
                    let value = e.target.value.replace(/\D/g, "");

                    // format with thousands separator
                    document.getElementById("addPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            </script>
            <div class="col-md-1"><input id="addCostPrice" type="number" step="0.01" name="cost_price" placeholder="التكلفة" class="form-control" required>
                <label id="addCostPriceLabel">

                </label>
            </div>
            <script>
                document.getElementById("addCostPrice").addEventListener("input", function(e) {
                    // remove any non-digit characters
                    let value = e.target.value.replace(/\D/g, "");

                    // format with thousands separator
                    document.getElementById("addCostPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            </script>

            <div class="col-md-1"><input type="number" name="stock_quantity" placeholder="الكمية" class="form-control" required></div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <!-- <option value=""></option> -->
                    <?php
                    foreach ($categoriesSelect as $i => $item) {
                        echo '<option value="' . $item["name"] . '">' . $item["name"] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2"><button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button></div>
        </form>

        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>ID</th>
                    <th>Name/الاسم</th>
                    <th>Barcode/باركود</th>
                    <th>Price/السعر</th>
                    <th>Cost Price/التكلفة</th>
                    <th>Stock Qtc/المخزن</th>
                    <th>Category/الفئة</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>




                <?php
                foreach ($data as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["id"] . '</td>';
                    echo '<td>' . $item["name"] . '</td>';
                    echo '<td>' . $item["barcode"] . '</td>';
                    echo '<td>' .  number_format($item["price"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["cost_price"], 2, ".", ",") . '</td>';
                    echo '<td>' . $item["stock_quantity"] . '</td>';
                    echo '<td>' . $item["category"] . '</td>';
                    echo '<td><a href="?delete=' . $item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a> &nbsp;';
                    echo '<a data-category=' . $item["category"] . ' data-id=' . $item['id'] . ' data-stock_quantity="' . $item["stock_quantity"] . '" data-name="' . $item["name"] . '" data-barcode="' . $item["barcode"] . '" data-price="' . $item["price"] . '" data-cost_price="' . $item["cost_price"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="showDataBeforeEdit(this)">Edit</a></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <form action="editProduct.php" method="GET">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button  type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        <h5 class="modal-title" id="editProductModalLabel">تعديل المنتج</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productId" class="form-label">رقم التعريف</label>
                            <input type="text" name="productId" class="form-control" id="productId" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">اسم المنتج</label>
                            <input type="text" class="form-control" id="productName" name="productName" placeholder="الاسم">
                        </div>
                        <div class="mb-3">
                            <label for="barcode" class="form-label">باركود</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="باركود">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">السعر</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="السعر">
                                <label id="editPriceLabel"></label>
                                <script>
                                    document.getElementById("price").addEventListener("input", function(e) {
                                        // remove any non-digit characters
                                        let value = e.target.value.replace(/\D/g, "");

                                        // format with thousands separator
                                        document.getElementById("editPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    });
                                </script>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="costPrice" class="form-label">سعر التكلفة</label>
                                <input type="number" step="0.01" class="form-control" id="costPrice" name="costPrice" placeholder="التكلفة">
                                <label id="editCostPriceLabel"></label>
                                <script>
                                    document.getElementById("costPrice").addEventListener("input", function(e) {
                                        // remove any non-digit characters
                                        let value = e.target.value.replace(/\D/g, "");

                                        // format with thousands separator
                                        document.getElementById("editCostPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stockQtc" class="form-label">الكمية</label>
                            <input type="number" step="0.01" class="form-control" id="stockQtc" name="stockQuantity" placeholder="الكمية">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">الفئة</label>
                            <select class="form-select" id="category" name="categoryId">
                                <!-- <option value=""></option> -->
                                <?php
                                foreach ($categoriesSelect as $i => $item) {
                                    echo '<option value="' . $item["name"] . '">' . $item["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="reset" class="btn btn-secondary">ا</button> -->
                        <button type="submit" name="editProduct" class="btn btn-primary">حفظ التغييرات</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</body>
<script src="../common/bootstrap.js"></script>
<script>
    function showDataBeforeEdit(e) {
        let productId = document.getElementById("productId");
        let productName = document.getElementById("productName");
        let barcode = document.getElementById("barcode");
        let price = document.getElementById("price");
        let costPrice = document.getElementById("costPrice");
        let stockQtc = document.getElementById("stockQtc");
        let category = document.getElementById("category");

        productId.value = e.dataset.id
        productName.value = e.dataset.name
        barcode.value = e.dataset.barcode
        price.value = e.dataset.price
        costPrice.value = e.dataset.cost_price
        stockQtc.value = e.dataset.stock_quantity
        category.value = e.dataset.category
    }


    // function openDialog() {
    //     document.getElementById("dialog").style.display = "flex";
    // }

    // function closeDialog() {
    //     document.getElementById("dialog").style.display = "none";
    // }

    let urlParams = new URLSearchParams(window.location.search);

    // Check if "error" exists
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