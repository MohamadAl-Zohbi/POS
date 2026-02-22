<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';
if (isset($_POST['add_product'])) {
    $name      = $_POST['name'];
    $barcode   = $_POST['barcode'];
    $price     = $_POST['price'];
    $costprice = $_POST['cost_price'];
    $stock_quantity  = $_POST['stock_quantity'];
    $category  = $_POST['category'];
    $currency  = $_POST['currency'];
    $img = "";
    if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
        $img = time() . "_" . $_FILES['img']['name'];
        $targetPath = "../uploads/" . $img;
        if (!move_uploaded_file($_FILES['img']['tmp_name'], $targetPath)) {
            die("Upload failed");
        }
    }
    $sql = "INSERT INTO products (name, barcode, price, cost_price, stock_quantity, category,currency,img) 
            VALUES (:name,:barcode,:price,:costprice,:stock_quantity,:category,:currency,:img)";

    $addProduct = $db->prepare($sql);
    $addProduct->bindParam(':name', $name);
    $addProduct->bindParam(':barcode', $barcode);
    $addProduct->bindParam(':price', $price);
    $addProduct->bindParam(':costprice', $costprice);
    $addProduct->bindParam(':stock_quantity', $stock_quantity);
    $addProduct->bindParam(':category', $category);
    $addProduct->bindParam(':currency', $currency);
    $addProduct->bindParam(':img', $img);
    if ($addProduct->execute()) {
        header('Location: products.php');
    }
}
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

// update product qtc
if (isset($_GET['qtc']) && isset($_GET['qtcId'])) {
    $id = $_GET['qtcId'];
    $productQtc = $db->prepare("UPDATE products set stock_quantity = stock_quantity + :qtc WHERE id = :id");
    $productQtc->bindParam(':qtc', $_GET['qtc']);
    $productQtc->bindParam(':id', $id);
    if (!$productQtc->execute()) {
        echo '<script>alert("حدث خطأ اثناء الاضافة الرجاء اعادت المحاولة")</script>';
    }
    header('Location: ?');
}


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

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأصناف</title>
    <link href="../common/bootstrap.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">

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

        td {
            max-width: 250px;
        }
    </style>
</head>

<body>
    <?php include_once "./navbar.php" ?>
    <div class="container">
        <br>
        <form method="POST" enctype="multipart/form-data" class="row g-2">
            <div class="col-md-2"><input type="text" name="name" placeholder="الاسم" class="form-control" required></div>
            <div class="col-md-1"><input type="text" name="barcode" placeholder="باركود" class="form-control" required></div>
            <div class="col-md-1"><input id="addPrice" type="number" step="0.01" name="price" placeholder="السعر" class="form-control" required>
                <label id="addPriceLabel">

                </label>
            </div>
            <script>
                document.getElementById("addPrice").addEventListener("input", function(e) {
                    let value = e.target.value.replace(/\D/g, "");
                    document.getElementById("addPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            </script>
            <div class="col-md-1"><input id="addCostPrice" type="number" step="0.01" name="cost_price" placeholder="التكلفة" class="form-control" required>
                <label id="addCostPriceLabel">
                </label>
            </div>
            <script>
                document.getElementById("addCostPrice").addEventListener("input", function(e) {
                    let value = e.target.value.replace(/\D/g, "");
                    document.getElementById("addCostPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            </script>
            <div class="col-md-1">
                <select name="currency" id="sessionCurrency" class="form-select">
                    <option value="usd">USD</option>
                    <option value="lbp">LBP</option>
                </select>
            </div>
            <div class="col-md-1">
                <input name="img" type="file" class="form-control">
            </div>
            <div class="col-md-1"><input type="number" name="stock_quantity" placeholder="الكمية" class="form-control" required></div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value=""></option>
                    <?php
                    foreach ($categoriesSelect as $i => $item) {
                        echo '<option value="' . $item["name"] . '">' . $item["name"] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2"><button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button></div>
        </form>
        <nav class="navbar navbar-light bg-light">
            <input class="form-control mr-sm-2" id="searchProducts" type="search" placeholder="ابحث عن المنتج">
            <br>
            <br>
            <button onclick="printReport()">طباعة</button>
        </nav>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>ID</th>
                    <th>Name/الاسم</th>
                    <th>Barcode/باركود</th>
                    <th>Price/السعر</th>
                    <th>Cost Price/التكلفة</th>
                    <th>Currency/العملة</th>
                    <th>Stock Qtc/المخزن</th>
                    <th>Category/الفئة</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productTable">
                <?php
                foreach ($data as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["id"] . '</td>';
                    echo '<td>' . $item["name"] . '</td>';
                    echo '<td>' . $item["barcode"] . '</td>';
                    echo '<td>' .  number_format($item["price"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["cost_price"], 2, ".", ",") . '</td>';
                    echo '<td>' . $item["currency"] . '</td>';
                    echo '<td>' . $item["stock_quantity"] . '</td>';
                    echo '<td>' . $item["category"] . '</td>';
                    echo '<td><a href="?delete=' . $item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a> &nbsp;';
                    echo '<a data-category="' . $item["category"] . '" data-id=' . $item['id'] . ' data-stock_quantity="' . $item["stock_quantity"] . '" data-name="' . $item["name"] . '" data-currency="' . $item["currency"] . '" data-barcode="' . $item["barcode"] . '" data-price="' . $item["price"] . '" data-cost_price="' . $item["cost_price"] . '" data-img="' . $item["img"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="showDataBeforeEdit(this)">Edit</a>&nbsp;
                    <a class="btn btn-secondary btn-sm btn-primary" onclick="editStockDetails(' . $item['id'] . ')">Add</a>
                    </td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <form action="editProduct.php" enctype="multipart/form-data" method="POST">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

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
                                        let value = e.target.value.replace(/\D/g, "");
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
                                        let value = e.target.value.replace(/\D/g, "");
                                        document.getElementById("editCostPriceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    });
                                </script>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">العملة</label>
                                <select class="form-select" id="currency" name="currency">
                                    <option value="usd">USD</option>
                                    <option value="lbp">LBP</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stockQtc" class="form-label">الكمية</label>
                            <input type="number" step="0.01" class="form-control" id="stockQtc" name="stockQuantity" placeholder="الكمية">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">الفئة</label>
                            <select class="form-select" id="category" name="categoryId">
                                <option value=""></option>
                                <?php
                                foreach ($categoriesSelect as $i => $item) {
                                    echo '<option value="' . $item["name"] . '">' . $item["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <!-- Preview -->
                            <div class="mb-3">
                                <label for="img" class="form-label">الصورة</label>
                                <input type="file" class="form-control" name="img">
                                <br>
                                <img id="imgPreview" name="imgPreview" src="../uploads/" alt="No Img" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editProduct" class="btn btn-primary">حفظ التغييرات</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</body>
<script src="../common/bootstrap.js"></script>
<script>
    const data = <?php echo json_encode($data) ?>;
    let dataSave = <?php echo json_encode($data) ?>;

    function showDataBeforeEdit(e) {
        let productId = document.getElementById("productId");
        let productName = document.getElementById("productName");
        let barcode = document.getElementById("barcode");
        let price = document.getElementById("price");
        let costPrice = document.getElementById("costPrice");
        let stockQtc = document.getElementById("stockQtc");
        let category = document.getElementById("category");
        let currency = document.getElementById("currency");
        let img = document.getElementById("imgPreview");

        productId.value = e.dataset.id
        productName.value = e.dataset.name
        barcode.value = e.dataset.barcode
        price.value = e.dataset.price
        costPrice.value = e.dataset.cost_price
        stockQtc.value = e.dataset.stock_quantity
        category.value = e.dataset.category
        currency.value = e.dataset.currency
        img.src = "../uploads/" + e.dataset.img

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
        if (sessionStorage.getItem("currency")) {
            document.getElementById("sessionCurrency").value = sessionStorage.getItem("currency");
        }
        document.getElementById("sessionCurrency").addEventListener("change", () => {
            sessionStorage.setItem("currency", document.getElementById("sessionCurrency").value);
        });

        document.getElementById("searchProducts").addEventListener("change", () => {
            const table = document.getElementById("productTable");
            table.innerHTML = "";
            const filtredData = searchProducts(document.getElementById("searchProducts").value);
            dataSave = filtredData;
            if (!filtredData.length) return;
            // const headerRow = document.createElement("tr");

            filtredData.forEach((item, i) => {
                table.innerHTML += `
                 <tr>
             <td> ${i+1}</td>
             <td>${item["id"]}</td>
             <td>${item["name"]}</td>
             <td>${item["barcode"]}</td>
             <td>${Number(item["price"]).toFixed(2)}</td>
             <td>${Number(item["cost_price"]).toFixed(2)}</td>
             <td>${item["currency"]}</td>
             <td>${item["stock_quantity"]}</td>
             <td>${item["category"]}</td>
             <td><a href="?delete=${item['id']}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> &nbsp;
             <a data-category="${item["category"]}" data-id=${item['id']} data-stock_quantity="${item["stock_quantity"]}" data-name="${item["name"]}" data-currency="${item["currency"]}" data-barcode="${item["barcode"]}" data-price="${item["price"]}" data-cost_price="${item["cost_price"]}" data-img="${item["img"]}" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="showDataBeforeEdit(this)">Edit</a>&nbsp;
             <a class="btn btn-secondary btn-sm btn-primary" onclick="editStockDetails(${item['id']})">Add</a>
             </td>
             </tr>
            `
            });
        });

    }

    function editStockDetails(id) {
        let qtc = Number(prompt("ادخل الكمية الجديدة:"));
        while (isNaN(qtc) || qtc == "") {
            qtc = prompt("ادخل الكمية الجديدة");
        }
        if (qtc === null) {
            return false;
        }
        let url = "?qtc=" + qtc + "&qtcId=" + id;
        window.location.href = url;
    }

    function searchProducts(searchTerm) {
        const term = searchTerm.toString().toLowerCase();

        return data.filter(product => {
            return Object.values(product).some(value =>
                value !== null &&
                value.toString().toLowerCase().includes(term)
            );
        });
    }

    function printReport() {
        localStorage.setItem("reportData", JSON.stringify(dataSave));
        window.open("../common/productsPrint.html", "_blank");
    }
</script>

</html>