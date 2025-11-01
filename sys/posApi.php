<?php
include_once "../common/connect.php";
header("Content-Type: application/json");

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

// Get raw POST body
//  {"items" , cart}
$data = json_decode(file_get_contents("php://input"), true);

// Check if data exists
if (!$data || !isset($data['items']) || !isset($data['total'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$userID;
$dollar;
$saleID;

session_start();
$selectUser = $db->prepare("SELECT * FROM users WHERE username = :username");
$selectUser->bindParam(':username', $_SESSION['username']);
if ($selectUser->execute()) {
    $selectUser = $selectUser->fetchAll(PDO::FETCH_ASSOC);
    if (count($selectUser) > 0) {
        $userID = $selectUser[0]['id'];
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "no user found"]);
        exit;
    }
}



$getDollar = $db->prepare("SELECT * FROM data");
if ($getDollar->execute()) {
    $getDollar = $getDollar->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDollar) > 0) {
        $dollar = $getDollar[0]['dollar'];
    }
}

$date      = date("Y-m-d H:i:s");
$totalUSD   = number_format(($data['total'] / $dollar), 2);
$totalLBP     = $data['total'];


$addSaleQuery = "INSERT INTO sales (date, user_id, total_amount_usd, total_amount_lbp) 
            VALUES (:date,:userID,:total_amount_usd,:total_amount_lbp)";

$addSale = $db->prepare($addSaleQuery);
$addSale->bindParam(':date', $date);
$addSale->bindParam(':userID', $userID);
$addSale->bindParam(':total_amount_usd', $totalUSD);
$addSale->bindParam(':total_amount_lbp', $totalLBP);

if ($addSale->execute()) {
}

$getSale = $db->prepare("SELECT MAX(id) as id FROM sales");
if ($getSale->execute()) {
    $getSale = $getSale->fetchAll(PDO::FETCH_ASSOC);
    if (count($getSale) > 0) {
        $saleID = $getSale[0]['id'];
    }
}

$sql = "";

// prepare the main query


foreach ($data['items'] as $i => $item) {
    $productID = $item["id"];
    $quantity = $item["qty"];
    $price = $item["price"];
    $currency = $item["currency"];
    // $sql .= "cx";
    // saleid
    $sql .= "INSERT INTO sale_items(sale_id,product_id,quantity,unit_price,currency,date) VALUES (".$saleID.",".$productID.",".$quantity.','.$price.',"'.$currency.'","'.$date.'");';
}


$addSalesLines = $db->prepare($sql);
if (!$addSalesLines->execute()) {
    echo json_encode(['we have a problem']);
}

// Example response

// Send response
echo json_encode(['details'=>"done"]);

