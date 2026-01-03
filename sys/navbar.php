<?php
include_once "./checkLogin.php";

$getDollar = $db->prepare("SELECT dollar FROM data");
$dollar;
if ($getDollar->execute()) {
    $getDollar = $getDollar->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDollar)) {
        $dollar = $getDollar[0]['dollar'];
    }
}
if (isset($_GET['closeDay'])) {
    $sql = "UPDATE data SET date = :date WHERE 1 = 1";
    $closeDay = $db->prepare($sql);
    $date = date("Y-m-d");
    $closeDay->bindParam(':date', $date);
    if ($closeDay->execute()) {
        header('Location: ' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
}

if (isset($_GET['updateDollar'])) {
    $sql = "UPDATE data SET dollar = :dado WHERE 1 = 1";
    $updateDado = $db->prepare($sql);
    $dado = $_GET['updateDollar'];
    $updateDado->bindParam(':dado', $dado);
    if ($updateDado->execute()) {
        header('Location: ' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">

            <?php
            $url = parse_url($_SERVER['REQUEST_URI']);
            if (str_contains($url['path'], "dashboard")) {
                echo "الصفحة الرئيسية";
            } else if (str_contains($url['path'], "products")) {
                echo "الاصناف";
            } else if (str_contains($url['path'], "category")) {
                echo "الفئة";
            } else if (str_contains($url['path'], "users")) {
                echo "المستخدمين";
            } else if (str_contains($url['path'], "customer")) {
                echo "العملاء";
            } else if (str_contains($url['path'], "bills")) {
                echo "الفواتير";
            } else if (str_contains($url['path'], "dailyReport")) {
                echo "التقرير اليومي";
            } else if (str_contains($url['path'], "productReport")) {
                echo "تقرير بالمبيعات";
            }else if (str_contains($url['path'], "settings")) {
                echo "الإعدادات";
            }



            ?>


        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./dashboard.php">الصفحة الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./products.php">الاصناف/products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./category.php">الفئة/category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./customers.php">العملاء/customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./users.php">المستخدمين/users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./bills.php">الفواتير/bills</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        المزيد
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./dailyReport.php">الرصيد اليومي</a></li>
                        <li><a class="dropdown-item" href="./productReport.php">تقرير بالمبيعات</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="?closeDay=">اغلاق اليوم</a></li>
                        <li><a class="dropdown-item" onclick="updateDollar()" href="">تعديل الدولار</a></li>
                        <li><a class="dropdown-item" href="./settings.php">الإعدادات</a></li>
                        <li><a class="dropdown-item" style="color: red;" href="./pos.php">POS</a></li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li> -->
            </ul>
            <form class="d-flex" role="search">
                <input style="direction:ltr; text-align:center;" class="form-control me-2" style="text-align: center;" type="text" value="<?php echo $dollar ?> L.L" readonly placeholder="Dollar" />
                &nbsp;
                <a href="./logout.php" class="btn btn-outline-warning" type="submit">Logout</a>
            </form>
        </div>
    </div>

    <script>
        function updateDollar() {
            let amount = prompt("ادخل المبلغ");
            while (isNaN(amount) || amount == "") {
                amount = prompt("ادخل المبلغ");
            }
            if (amount === null) {
                return false;
            }
            let url = "?updateDollar=" + amount;
            window.location.href = url;
        }
    </script>
</nav>
<?php include_once "./closeDay.php"; ?>