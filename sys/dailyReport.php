<?php
include_once '../common/connect.php';
include_once './check.php';
include_once './onlyAdmin.php';

// Get Users
$getUsers = $db->prepare("SELECT * FROM users");
//  WHERE category != ''
$users = [];
if ($getUsers->execute()) {
    while ($row = $getUsers->fetch(\PDO::FETCH_ASSOC)) {
        array_push($users, $row);
    }
}
// Select Bills 
$sales = $db->prepare("SELECT DATE(date) AS date,
    SUM(total_amount_lbp) AS total_lbp,
    SUM(total_amount_usd) AS total_usd
     FROM sales WHERE 
     date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "'
     GROUP BY DATE(date)
     ORDER BY DATE(date);
    ");
$salesSelect = [];
if ($sales->execute()) {
    while ($rowe = $sales->fetch(\PDO::FETCH_BOTH)) {
        array_push($salesSelect, $rowe);
    }
}

if (isset($_GET['search'])) {
    if ($_GET['user'] == "") {
        $sales = $db->prepare("SELECT DATE(date) AS date,
    SUM(total_amount_lbp) AS total_lbp,
    SUM(total_amount_usd) AS total_usd
     FROM sales WHERE 
     date >= '" . $_GET['from'] . "' AND date <  '" . date('Y-m-d', strtotime($_GET['to'] . ' +1 day')) . "'
     GROUP BY DATE(date)
     ORDER BY DATE(date);
    ");
    } else {
        $sales = $db->prepare("SELECT DATE(date) AS date,
    SUM(total_amount_lbp) AS total_lbp,
    SUM(total_amount_usd) AS total_usd
     FROM sales WHERE 
     date >= '" . $_GET['from'] . "' AND date <  '" . date('Y-m-d', strtotime($_GET['to'] . ' +1 day')) . "' AND
     user_id = :user_id
     GROUP BY DATE(date)
     ORDER BY DATE(date);
    ");
        $sales->bindParam(':user_id', $_GET['user']);
    }


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
            <div class="col-md-1">
                <select name="user" class="form-select">
                    <option value=""></option>
                    <?php
                    foreach ($users as $i => $item) {
                        $selected;
                        if (isset($_GET['user'])) {
                            $selected =  $_GET['user'] == $item["id"]  ? "selected" : "";
                        }
                        echo '<option value="' . $item["id"] . '" ' . $selected . '>' . $item["username"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" name="search" class="btn btn-primary w-100">البحث</button></div>
            <div class="col-md-2"><button type="button" onclick="location.replace('?')" name="clearSearch" class="btn btn-primary w-100">محو</button></div>
        </form>

        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>Date/التاريخ</th>
                    <th>Total LL/المبلغ</th>
                    <th>Total $/المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($salesSelect as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["date"] . '</td>';
                    echo '<td>' . number_format($item["total_lbp"], 2, ".", ",") . '</td>';
                    echo '<td>' . number_format($item["total_usd"], 2, ".", ",") . '</td>';
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