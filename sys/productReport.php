<?php
include_once '../common/connect.php';
include_once './check.php';
include_once './onlyAdmin.php';

// Select Bills 
$sales = $db->prepare("SELECT 
                    products.name,SUM(quantity) as quantity,SUM(quantity * unit_price) AS price,products.currency,SUM(quantity * cost_price) AS cost 
                    FROM products,sale_items
                    WHERE
                    sale_items.product_id = products.id AND
                    sale_items.date >= '" . date('Y-m-d') . "' AND sale_items.date <  '" . date('Y-m-d', strtotime('+1 day')) . "'
                    GROUP BY name
                    ORDER BY DATE(name);
                    ");
$salesSelect = [];
if ($sales->execute()) {
    while ($rowe = $sales->fetch(\PDO::FETCH_BOTH)) {
        array_push($salesSelect, $rowe);
    }
}


// date('Y-m-d', strtotime($_GET['to'] . ' +1 day'))     next date query 
if (isset($_GET['search'])) {
    $sales = $db->prepare("SELECT 
                    products.name,SUM(quantity) as quantity,SUM(quantity * unit_price) AS price,products.currency,SUM(quantity * cost_price) AS cost 
                    FROM products,sale_items
                    WHERE
                    sale_items.product_id = products.id AND
                    sale_items.date >= '" . date($_GET['from']) . "' AND sale_items.date <  '" . date('Y-m-d', strtotime($_GET['to'] . ' +1 day')) . "'
                    GROUP BY name
                    ORDER BY DATE(name);
                    ");

    $salesSelect = [];
    if ($sales->execute()) {
        while ($rowe = $sales->fetch(\PDO::FETCH_BOTH)) {
            array_push($salesSelect, $rowe);
        }
    }
}

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
        }
    </style>
</head>

<body>
    <?php include_once "./navbar.php" ?>

    <div class="container">
        <br>
        <form method="GET" class="mb-4 row g-2">
            <div class="col-md-2"><input type="date" name="from" class="form-control" required value="<?php echo isset($_GET['from']) ? $_GET['from'] : date('Y-m-d'); ?>"></div>
            <div class="col-md-2"><input type="date" name="to" class="form-control" required value="<?php echo isset($_GET['to']) ? $_GET['to'] : date('Y-m-d') ?>"></div>
            <div class="col-md-2"><button type="submit" name="search" class="btn btn-primary w-100">البحث</button></div>
            <div class="col-md-2"><button type="button" onclick="location.replace('?')" name="clearSearch" class="btn btn-primary w-100">محو</button></div>
        </form>

        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>Name/الاسم</th>
                    <th>quantity/الكمية</th>
                    <th>Total/المبلغ</th>
                    <th>Cost/التكلفة</th>
                    <th>Profit/الربح</th>
                    <th>Currency/العملة</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($salesSelect as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["name"] . '</td>';
                    echo '<td>' . number_format($item["quantity"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["price"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["cost"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["price"] - $item["cost"], 2, ".", ",") . '</td>';
                    echo '<td>' . $item["currency"] . '</td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




</body>
<script src="../common/bootstrap.js"></script>
<script>

</script>

</html>