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

    $sql = "INSERT INTO products (name, barcode, price, cost_price, stock_quantity, category_id) 
            VALUES (:name,:barcode,:price,:costprice,:stock_quantity,:category)";

    $addProduct = $db->prepare($sql);
    $addProduct->bindParam(':name',$name);
    $addProduct->bindParam(':barcode',$barcode);
    $addProduct->bindParam(':price',$price);
    $addProduct->bindParam(':costprice',$costprice);
    $addProduct->bindParam(':stock_quantity',$stock_quantity);
    $addProduct->bindParam(':category',$category);
    if ($addProduct->execute()) {
        header('Location: products.php');
    }
}




// --- Delete Product ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleting = $db->prepare("DELETE FROM products WHERE id=:id");
    $deleting->bindParam(':id', $id);
    if ($deleting->execute()) header('Location: products.php');;
}


// --- Edit Product ---
if (isset($_POST['editProduct'])) {
    $name      = $_POST['name'];
    $barcode   = $_POST['barcode'];
    $price     = $_POST['price'];
    $costprice = $_POST['cost_price'];
    $stock_quantity  = $_POST['stock_quantity'];
    $category  = $_POST['category'];

    $sql = "UPDATE products SET name='Moudi', barcode='123', price='22', cost_price='12', stock_quantity='22', category_id=10 WHERE id = 1";

    $addProduct = $db->prepare($sql);
    $addProduct->bindParam(':name',$name);
    $addProduct->bindParam(':barcode',$barcode);
    $addProduct->bindParam(':price',$price);
    $addProduct->bindParam(':costprice',$costprice);
    $addProduct->bindParam(':stock_quantity',$stock_quantity);
    $addProduct->bindParam(':category',$category);
    if ($addProduct->execute()) {
        header('Location: products.php');
    }
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products Control</title>
    <link href="../common/bootstrap.css" rel="stylesheet">
    <style>
        #card {
            position: fixed;
            margin: auto;
        }
    </style>
</head>

<body class="bg-light p-4">

    <div class="container">
        <h2 class="mb-4">Products Control</h2>

        <!-- Add Product Form -->
        <form method="POST" class="mb-4 row g-2">
            <div class="col-md-2"><input type="text" name="name" placeholder="Name" class="form-control" required></div>
            <div class="col-md-2"><input type="text" name="barcode" placeholder="Barcode" class="form-control" required></div>
            <div class="col-md-1"><input type="text" step="0.01" name="price" placeholder="Price" class="form-control" required></div>
            <div class="col-md-1"><input type="text" step="0.01" name="cost_price" placeholder="Cost" class="form-control" required></div>
            <div class="col-md-1"><input type="text" name="stock_quantity" placeholder="Stock" class="form-control" required></div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value=""></option>
                    <?php
                    foreach ($categoriesSelect as $i => $item) {
                        echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
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
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Price</th>
                    <th>Cost Price</th>
                    <th>Stock Qtc</th>
                    <th>Category</th>
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
                    echo '<td>' . $item["price"] . '</td>';
                    echo '<td>' . $item["cost_price"] . '</td>';
                    echo '<td>' . $item["stock_quantity"] . '</td>';
                    echo '<td>' . $item["category_id"] . '</td>';
                    echo '<td><a href="?delete=' . $item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a> &nbsp;';
                    echo '<a data-category='.$item["category_id"].' data-id='.$item['id'].' data-stock_quantity="' . $item["stock_quantity"].'" data-name="' . $item["name"] . '" data-barcode="' . $item["barcode"] . '" data-price="' . $item["price"] . '" data-cost_price="' . $item["cost_price"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="showDataBeforeEdit(this)">Edit</a></td>';
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
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <label for="productId" class="form-label">Product ID</label>
                            <input type="text" class="form-control" id="productId" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name">
                        </div>
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Enter barcode">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="costPrice" class="form-label">Cost Price</label>
                                <input type="text" class="form-control" id="costPrice" name="costPrice" placeholder="Enter cost price">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stockQtc" class="form-label">Stock Quantity</label>
                            <input type="text" class="form-control" id="stockQtc" name="stockQuantity" placeholder="Enter stock quantity">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="categoryId">
                                <option value=""></option>
                                <?php
                                foreach ($categoriesSelect as $i => $item) {
                                    echo '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" name="editProduct" class="btn btn-primary">Save Changes</button>
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
</script>

</html>