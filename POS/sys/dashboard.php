    <?php
    include_once '../common/connect.php';
    include_once './checkLogin.php';
    include_once './onlyAdmin.php';
    $getUserCount = $db->prepare("SELECT count(id) as users FROM users");
    $users;
    if ($getUserCount->execute()) {
        $getUserCount = $getUserCount->fetchAll(PDO::FETCH_ASSOC);
        if (count($getUserCount)) {
            $users = $getUserCount[0]['users'];
        }
    }
    $getProductCount = $db->prepare("SELECT count(id) as products FROM products");
    $products;
    if ($getProductCount->execute()) {
        $getProductCount = $getProductCount->fetchAll(PDO::FETCH_ASSOC);
        if (count($getProductCount)) {
            $products = $getProductCount[0]['products'];
        }
    }
    $getCategoryCount = $db->prepare("SELECT count(name) as categories FROM categories");
    $categories;
    if ($getCategoryCount->execute()) {
        $getCategoryCount = $getCategoryCount->fetchAll(PDO::FETCH_ASSOC);
        if (count($getCategoryCount)) {
            $categories = $getCategoryCount[0]['categories'];
        }
    }

    $getSalesCount = $db->prepare("SELECT COUNT(id) AS sales FROM sales WHERE date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) ."';");
    $sales;
    if ($getSalesCount->execute()) {
        $getSalesCount = $getSalesCount->fetchAll(PDO::FETCH_ASSOC);
        if (count($getSalesCount)) {
            $sales = $getSalesCount[0]['sales'];
        }
    }

    ?>


    <!DOCTYPE html>
    <html lang="ar" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">
        <title>الصفحة الرئيسية</title>
        <style>
            .grid1 {
                float: right;
                top: 0px;
                right: 0px;
            }

            .grid2 {
                float: left;
                padding: 10px;
                text-align: center;
                width: calc(100% - 230px);
                margin: auto;
            }

            @media screen and (min-width: 1500px) {
                .grid {
                    position: relative;
                    width: 1500px;
                    margin: auto;
                }
            }

            :root {
                --primary: #007bff;
                --secondary: #6c757d;
                --success: #28a745;
                --info: #17a2b8;
                --text: #333;
            }

            body {
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f8f9fa;
            }

            .container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
            }

            .card {
                background: #fff;
                border: none;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                padding: 20px;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            }

            .card h4 {
                margin: 0 0 10px;
                color: var(--text);
            }

            .circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 20px;
                margin-right: 10px;
                flex-shrink: 0;
            }

            .circle-primary {
                background: var(--primary);
            }

            .circle-secondary {
                background: var(--secondary);
            }

            .circle-success {
                background: var(--success);
            }

            .circle-info {
                background: var(--info);
            }

            .stat {
                display: flex;
                align-items: center;
                margin-top: 10px;
            }

            .stat-number {
                font-size: 24px;
                font-weight: bold;
                color: var(--text);
            }
        </style>
        <link href="../common/bootstrap.css" rel="stylesheet">

    </head>

    <body>
        <?php include_once 'navbar.php' ?>
        <br>
        <div class="container">
            <div class="card">
                <h4>Category</h4>
                <div class="stat">
                    <div class="circle circle-primary">C</div>
                    <div class="stat-number"><?php echo $categories ?></div>
                </div>
            </div>

            <div class="card">
                <h4>Orders</h4>
                <div class="stat">
                    <div class="circle circle-secondary">O</div>
                    <div class="stat-number"><?php echo $sales ?></div>
                </div>
            </div>

            <div class="card">
                <h4>Product</h4>
                <div class="stat">
                    <div class="circle circle-success">P</div>
                    <div class="stat-number"><?php echo $products ?></div>
                </div>
            </div>

            <div class="card">
                <h4>User</h4>
                <div class="stat">
                    <div class="circle circle-info">U</div>
                    <div class="stat-number"><?php echo $users ?></div>
                </div>
            </div>
        </div>
        </div>
    </body>
    <script src="../common/bootstrap.js"></script>

    </html>