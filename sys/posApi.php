<?php
include_once "../common/connect.php";
include_once './checkLogin.php';

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
if (!$data || !isset($data['items']) || !isset($data['total']) || $data['total'] == 0) {
    // http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$userID;
$dollar;
$saleID;
$customerId;

// session_start();
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


$getDate = $db->prepare("SELECT date FROM data");
$dataDate;
if ($getDate->execute()) {
    $getDate = $getDate->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDate)) {
        $dataDate = $getDate[0]['date'];
    }
}

$date      = $dataDate . " " . date("H:i:s");
$totalUSD   = number_format(($data['total'] / $dollar), 2, ".", "");
$totalLBP     = $data['total'];


$addSaleQuery = "INSERT INTO sales (date, user_id, total_amount_usd, total_amount_lbp) 
            VALUES (:date,:userID,:total_amount_usd,:total_amount_lbp)";

$addSale = $db->prepare($addSaleQuery);
$addSale->bindParam(':date', $date);
$addSale->bindParam(':userID', $userID);
$addSale->bindParam(':total_amount_usd', $totalUSD);
$addSale->bindParam(':total_amount_lbp', $totalLBP);



if ($addSale->execute()) {
    $getSale = $db->prepare("SELECT MAX(id) as id FROM sales");
    if ($getSale->execute()) {
        $getSale = $getSale->fetchAll(PDO::FETCH_ASSOC);
        if (count($getSale) > 0) {
            $saleID = $getSale[0]['id'];
        }
    }
    // update the cusmtomer amount
    if (isset($data['customerId'])) {

        if ($data['customerId'] != "") {
            $customerId =  $data['customerId'];
            $customerQuery = "UPDATE customers SET balance =  balance + :balance WHERE id=:id;";

            $updateCustomerAmount = $db->prepare($customerQuery);

            $updateCustomerAmount->bindParam(':balance', $totalLBP);
            $updateCustomerAmount->bindParam(':id', $customerId);
            $updateCustomerAmount->execute();

            // add the log debts with the facture id
            $addDebtLog = $db->prepare("INSERT INTO customer_debts_logs(id,customer_id,facture_id,amount,date)
             VALUES
             (null,:customer_id,:facture_id,:amount,'$date');");
            $addDebtLog->bindParam(':customer_id', $customerId);
            $addDebtLog->bindParam(':facture_id', $saleID);
            $addDebtLog->bindParam(':amount', $totalLBP);

            if ($addDebtLog->execute()) {
                $addDebtLog = $addDebtLog->fetchAll(PDO::FETCH_ASSOC);
                if (count($addDebtLog) > 0) {
                    $saleID = $addDebtLog[0]['id'];
                }
            }
        }
    }
}



$sql = "INSERT INTO sale_items(sale_id,product_id,quantity,unit_price,currency,date) VALUES";

$sqlMinus = "UPDATE products SET stock_quantity = CASE";
$ar = array();
// prepare the main query

//
// UPDATE products
// SET price = CASE
//     WHEN id = 1 THEN 10
//     WHEN id = 2 THEN 15
//     WHEN id = 3 THEN 20
// END
// WHERE id IN (1, 2, 3);
//


$index = 0;
foreach ($data['items'] as $i => $item) {
    $index++;
    $productID = $item["id"];
    $quantity = $item["qty"];
    $price = $item["price"];
    $currency = $item["currency"];
    array_push($ar, $productID);
    // $sql .= "cx";
    // saleid
    if ($index == 1) {
        $sql .= "(" . $saleID . "," . $productID . "," . $quantity . ',' . $price . ',"' . $currency . '","' . $date . '")';
    } else {
        $sql .= ",(" . $saleID . "," . $productID . "," . $quantity . ',' . $price . ',"' . $currency . '","' . $date . '")';
    }
    $sqlMinus .= " WHEN id = $productID THEN stock_quantity - $quantity ";
}
$sqlMinus .= "END WHERE id IN (";
foreach ($ar as $i => $item) {
    $sqlMinus .= $i == count($ar) - 2 ? "$item,":"$item";
}
$sqlMinus .= ");";

$editQtc = $db->prepare($sqlMinus);
if($editQtc->execute()){

}


$addSalesLines = $db->prepare($sql);
if (!$addSalesLines->execute()) {
    echo json_encode(['we have a problem in add lines']);
}
// Send response
//done
echo json_encode(['details' => "done","ar" =>$sqlMinus]);
// exit;
