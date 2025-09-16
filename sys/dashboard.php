<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Awesome Sidebar</title>
    <style>
        .grid1 {
            float: right;

            top: 0px;
            right: 0px;
            /* margin: 0px 10px; */
            /* grid-row:auto; */
            /* grid-column: 0; */
        }

        .grid2 {
            float: left;
            padding: 10px;
            text-align: center;
            width: calc(100% - 230px);
            /* max-width: 1200px; */
            margin: auto;

            /* background-color: red; */
            /* margin: 10px; */

            /* grid-row:auto; */
            /* grid-column: 1; */
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
            /* padding: 20px; */
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

    <?php include_once 'check.php' ?>
    <?php include_once './onlyAdmin.php' ?>

    <!-- <div class="grid"> -->
        <!-- <div class="grid1"> -->
            <?php include_once 'navbar.php' ?>

        <!-- </div> -->
<br>

        <!-- <div class="grid2" id="grid2" style="text-align:center;"> -->
            <!-- <input type="number" style="width: 100px;" readonly placeholder="Dollar" step="0.01" class="form-control"> -->

            <div class="container">
                <div class="card">
                    <h4>Category</h4>
                    <div class="stat">
                        <div class="circle circle-primary">C</div>
                        <div class="stat-number">30</div>
                    </div>
                </div>

                <div class="card">
                    <h4>Orders</h4>
                    <div class="stat">
                        <div class="circle circle-secondary">O</div>
                        <div class="stat-number">20</div>
                    </div>
                </div>

                <div class="card">
                    <h4>Product</h4>
                    <div class="stat">
                        <div class="circle circle-success">P</div>
                        <div class="stat-number">120</div>
                    </div>
                </div>

                <div class="card">
                    <h4>User</h4>
                    <div class="stat">
                        <div class="circle circle-info">U</div>
                        <div class="stat-number">4</div>
                    </div>
                </div>
            </div>

        <!-- </div> -->
    </div>
</body>
<script src="../common/bootstrap.js"></script>
</html>