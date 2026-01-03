<?php
include_once './checkLogin.php';
include_once './onlyAdmin.php';
function validateInput($input, $type, $min = null, $max = null)
{
    $input = trim($input);

    switch ($type) {
        case 'string':
            if (!is_string($input)) return false;
            if ($min !== null && strlen($input) < $min) return false;
            if ($max !== null && strlen($input) > $max) return false;
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        case 'int':
            if (filter_var($input, FILTER_VALIDATE_INT) === false) return false;
            $input = (int)$input;
            if ($min !== null && $input < $min) return false;
            if ($max !== null && $input > $max) return false;
            return $input;

        case 'float':
            if (filter_var($input, FILTER_VALIDATE_FLOAT) === false) return false;
            return (float)$input;

        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL);

        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL);

        case 'bool':
            return filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        default:
            return false;
    }
}

if (isset($_GET['editProduct'])) {
    $name = $_GET['productName'];
    $barcode = $_GET['barcode'];
    $cost_price = $_GET['costPrice'];
    $price = $_GET['price'];
    $stock_quantity = $_GET['stockQuantity'];
    $category = $_GET['categoryId'];
    $currency = $_GET['currency'];
    $id  = $_GET['productId'];

    if (!validateInput($cost_price, 'float')) {
        header("Location: ./products.php?error=input error cost price should be a number");
    } else if (!validateInput($price, 'float')) {
        header("Location: ./products.php?error=input error price should be a number");
    } else if (!validateInput($stock_quantity, 'float')) {
        header("Location: ./products.php?error=input error stock quantity should be a number");
    }

    $sql = "UPDATE products SET name = :name, barcode = :barcode, price = :price, cost_price = :costprice, stock_quantity = :stock_quantity, category = :category, currency = :currency WHERE id = :id";
    $editProduct = $db->prepare($sql);
    $editProduct->bindParam(':name', $name);
    $editProduct->bindParam(':barcode', $barcode);
    $editProduct->bindParam(':price', $price);
    $editProduct->bindParam(':costprice', $cost_price);
    $editProduct->bindParam(':stock_quantity', $stock_quantity);
    $editProduct->bindParam(':category', $category);
    $editProduct->bindParam(':currency', $currency);
    $editProduct->bindParam(':id', $id);
    if ($editProduct->execute()) {
        header('Location: ./products.php');
    }
} else {
    header("Location: ../notAllowed.php");
}
