<?php
include_once "../common/connect.php";
include_once './checkLogin.php';
include_once './onlyAdmin.php';

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
if (!$data || !isset($data['qtc']) || $data['qtc'] == 0) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$qtc  = $data['qtc'];

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

// Send response
//done
echo json_encode(['details' => "done"]);
// exit;
