<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';

if (isset($_POST['add_category'])) {
    $name      = $_POST['name'];
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $addCategory = $db->prepare($sql);
    $addCategory->bindParam(':name', $name);
    if ($addCategory->execute()) {
        header('Location: category.php');
    }
}


// --- Delete Category ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $deleteSale = $db->prepare("DELETE FROM sales WHERE id=:id");
    $deleteSale->bindParam(':id', $id);

    $deleteSaleItems = $db->prepare("DELETE FROM sale_items WHERE sale_id=:id");
    $deleteSaleItems->bindParam(':id', $id);

    if ($deleteSaleItems->execute()) {
        if ($deleteSale->execute()) {
            header('Location: bills.php');
        } else {
            echo '<script>alert("هناك مشكلة يمكن ان تأثر على الجردة")</script>';
        }
    } else {
        echo '<script>alert("خطأ مجهول")</script>';
    }
}

// Select Bills 
$sales = $db->prepare("SELECT * FROM sales WHERE date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "';");
$salesSelect = [];
if ($sales->execute()) {
    while ($rowe = $sales->fetch(\PDO::FETCH_BOTH)) {
        array_push($salesSelect, $rowe);
    }
}

if (isset($_GET['search'])) {
    $sales = $db->prepare("SELECT * FROM sales WHERE date >= '" . $_GET['from'] . "' AND date <  '" . date('Y-m-d', strtotime($_GET['to'] . ' +1 day')) . "';");
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
    <title>الفواتير</title>
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">
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
                    <th>ID</th>
                    <th>Date/التاريخ</th>
                    <th>Total LL/المبلغ</th>
                    <th>Total $/المبلغ</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>




                <?php
                foreach ($salesSelect as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item['id'] . '</td>';
                    echo '<td>' . $item["date"] . '</td>';
                    echo '<td>' . number_format($item["total_amount_lbp"], 2, ".", ",") . '</td>';
                    echo '<td>' . $item["total_amount_usd"] . '</td>';

                    echo '<td><a href="?delete=' . $item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">حذف</a> &nbsp;';
                    echo '<button onclick="openFactureDetails(' . $item['id'] . ')" class="btn btn-secondary btn-sm btn-warning"  >Details</button></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




    <div class="modal fade" id="showSale" tabindex="-1" aria-labelledby="showSaleLabel" aria-hidden="true">
        <form method="GET">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        <h5 class="modal-title">تفاصيل الفاتورة</h5>
                    </div>

                    <div class="mb-3" style="text-align: center;">
                        <label for="saleId" class="form-label">المعرف</label>
                        <input readonly style="max-width: 500px; margin:auto;text-align:center;" type="text" class="form-control" id="id" placeholder="المعرف">
                    </div>

                    <?php
                    foreach ($saleCardDetails as $i => $item) {
                        echo `<div class="mb-3" style="text-align: center;">
                        <label for="saleId" class="form-label">المعرف</label>
                        <input value="` . $item['date'] . `" readonly style="max-width: 500px; margin:auto;text-align:center;" type="text" class="form-control" id="id" placeholder="المعرف">
                        </div>`;
                    }
                    ?>

                    <!-- <div class="modal-footer">
                        <button type="submit" name="editCategory" class="btn btn-primary">حفظ التغييرات</button>
                    </div> -->

                </div>
            </div>
        </form>
    </div>
   
</body>
<script src="../common/bootstrap.js"></script>
<script>
    // function showDataBeforeEdit(e) {
    //     let id = document.getElementById("id");
    //     let date = document.getElementById("date");
    //     let lbp = document.getElementById("total_amount_lbp");
    //     let usd = document.getElementById("total_amount_usd");
    //     id.value = e.dataset.id
    //     date.value = e.dataset.date
    //     lbp.value = e.dataset.total_amount_lbp
    //     usd.value = e.dataset.total_amount_usd
    // }

    let urlParams = new URLSearchParams(window.location.search);

    // Check if "error" exists
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


        if (urlParams.has("details")) {

            window.open('../common/printDetails.php?id=' + urlParams.get('details'), "_blank", "width=416,height=400,left=200,top=100");


            // console.log(urlParams.get("details"))
            // Create the iframe element
            // const iframe = document.createElement('iframe');
            // // const close = document.createElement('button');

            // // Set iframe attributes
            // iframe.src = '../common/printDetails.php?id=' + urlParams.get('details'); // URL to display
            // iframe.width = 100 + '%';
            // iframe.height = 100 + '%';
            // iframe.style.border = '1px solid #ccc';
            // iframe.style.position = 'fixed';
            // iframe.style.top = 0 + 'px';
            // iframe.style.background = 'rgba(0, 0, 0, 0.7)';
            // iframe.style['z-index'] = '100';
            // iframe.id = "iframe";

            // // Optionally, set other properties
            // iframe.allow = 'fullscreen';
            // iframe.loading = 'lazy';

            // // Add it to the page (for example, inside a div)

            // document.body.appendChild(iframe);
            // document.getElementById("iframe").appendChild(close);

        }
    }

    function closeFactureDetails() {
        document.getElementById("iframe").remove();
    }

    function openFactureDetails(id) {
            window.open('../common/printDetails.php?id=' + id, "_blank", "width=416,height=400,left=200,top=100");
    }
</script>

</html>