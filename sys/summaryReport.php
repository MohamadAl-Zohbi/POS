<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';
// الديون التي دينتها اليوم done
// الديون التي دفعت اليوم done
// رصيد الصندوق done
// اجمالي الديون done
// اجمالي مبيعات اليوم done

// sales
$sales = $db->prepare("SELECT 
SUM(total_amount_usd) as usd,SUM(total_amount_lbp) as lbp
                    FROM sales
                    WHERE
                    date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "';");
if ($sales->execute()) {
    $sales = $sales->fetchAll(PDO::FETCH_ASSOC);
    $sales = $sales[0];
}

// debts paid
$debtsPaid = $db->prepare("SELECT 
SUM(amount) as amount
                    FROM customer_payment
                    WHERE
                    date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "';");
if ($debtsPaid->execute()) {
    $debtsPaid = $debtsPaid->fetchAll(PDO::FETCH_ASSOC);
    $debtsPaid = $debtsPaid[0];
}

// debts for today
$todayDebts = $db->prepare("SELECT 
SUM(amount) as amount
                    FROM customer_debts_logs
                    WHERE
                    date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "';");
if ($todayDebts->execute()) {
    $todayDebts = $todayDebts->fetchAll(PDO::FETCH_ASSOC);
    $todayDebts = $todayDebts[0];
}

// total debts
$totalDebts = $db->prepare("SELECT SUM(balance) as amount FROM customers;");
if ($totalDebts->execute()) {
    $totalDebts = $totalDebts->fetchAll(PDO::FETCH_ASSOC);
    $totalDebts = $totalDebts[0];
}

$balanceLBP = $sales['lbp'] + $debtsPaid['amount'] - $todayDebts['amount'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تقرير مختصر</title>
    <link href="../common/bootstrap.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">
    <style>
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
        <table class="table table-bordered table-striped">
            <tr>
                <th>اجمالي المبيعات بالدولار</th>
                <th>اجمالي المبيعات بالليرة</th>
                <th>اجمالي الديون المدفوعة</th>
                <th>اجمالي الديون</th>
                <th>اجمالي ديون اليوم</th>
                <th>رصيد الصندوق</th>
            </tr>

            <tr>
                <td><?php echo  number_format($sales['usd'], 2, ".", ",") ?></td>
                <td><?php echo number_format($sales['lbp'], 2, ".", ",") ?></td>
                <td><?php echo number_format($debtsPaid['amount'], 2, ".", ",") ?></td>
                <td><?php echo number_format($totalDebts['amount'], 2, ".", ",") ?></td>
                <td><?php echo number_format($todayDebts['amount'], 2, ".", ",") ?></td>
                <td><?php echo number_format($balanceLBP, 2, ".", ",") ?></td>
            </tr>
        </table>
    </div>
</body>
<script src="../common/bootstrap.js"></script>

</html>