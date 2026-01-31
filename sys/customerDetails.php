<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';


$customerPayments = $db->prepare("SELECT * FROM customer_payment WHERE customer_id=" . $_GET['id'] . " ORDER BY date DESC;");
if ($customerPayments->execute()) {
    $customerPayments = $customerPayments->fetchAll(PDO::FETCH_ASSOC);
}

$customer = $db->prepare("SELECT * FROM customers WHERE id=" . $_GET['id'] . ";");
if ($customer->execute()) {
    $customer = $customer->fetchAll(PDO::FETCH_ASSOC);
    $customer = $customer[0];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <link href="../common/bootstrap.css" rel="stylesheet">

    <!-- <title>POS Facture</title> -->
    <style>
        *{
            text-align: center;
        }
    </style>
</head>
    <?php include_once "navbar.php"?>

<body>
<h1>
    <?php echo $customer['name'];?>
</h1>
        <table class="table table-bordered table-striped">
             <thead class="table-dark">
                 <tr>
                     <th>Index</th>
                     <!-- <th>Id/رقم التعريف</th> -->
                     <th>date/التاريخ</th>
                     <th>amount/المبلغ</th>
                     <th>Type</th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                    foreach ($customerPayments as $i => $item) {
                        echo "<tr>";
                        echo '<td>' . $i + 1 . '</td>';
                        // echo '<td>' . $item["customer_id"] . '</td>';
                        echo '<td>' . $item["date"] . '</td>';
                        echo '<td>' . number_format($item["amount"], 2, ".", ",") . 'L.L</td>';
                        echo '<td>' . $item["type"] . '</td>';

                        echo "</tr>";
                    }
                    ?>
             </tbody>
         </table>
</body>
<script src="../common/bootstrap.js"></script>

</html>