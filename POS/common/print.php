<?php
include_once '../common/connect.php';
$getData = $db->prepare("SELECT * FROM data");
$data;
if ($getData->execute()) {
    $getData = $getData->fetchAll(PDO::FETCH_ASSOC);
    $data = $getData[0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>POS Facture</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 3mm;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            font-size: 12px;
            text-align: left;
            padding: 2px 0;
        }

        th {
            border-bottom: 1px dashed #000;
        }

        tr {
            margin-bottom: 20px;
        }

        .totals {
            border-top: 1px dashed #000;
            margin-top: 5px;
            padding-top: 5px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 2px 0;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 11px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SuperMarket <?php echo $data['company_name'] ?></h2>
        <p><?php echo $data['address'] ?></p>
        <p>Tel: <?php echo $data['tel'] ?></p>
        <p>Date: <?php date_default_timezone_set('Asia/Beirut');
                    echo date("Y-m-d H:i:s"); ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="containar">
        </tbody>
    </table>
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td id="subTotal" style="text-align:right;">175,000</td>
            </tr>
            <tr>
                <td>Tax (11%)</td>
                <td id="tax" style="text-align:right;"></td>
            </tr>
            <tr style="font-weight:bold;">
                <td>Total</td>
                <td id="total" style="text-align:right;"></td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <p>Thank you for shopping!</p>
        <p>Visit us again 🌟</p>
    </div>
    <script>
        onload = () => {
            let urlParams = new URLSearchParams(window.location.search);
            let data = JSON.parse(urlParams.get("data"))
            let total = JSON.parse(urlParams.get("total"))

            document.getElementById("subTotal").innerText = formatNumber(total);
            document.getElementById("tax").innerText = formatNumber(total * 0.11);
            document.getElementById("total").innerText = formatNumber((total * 0.11) + total);

            let body = document.getElementById("containar");
            Object.entries(data).forEach(([key, value]) => {
                body.innerHTML +=
                    `
                    <tr>
                    <td style="max-width:100px;">${key}</td>
                    <td>${formatNumber(value.qty)}</td>
                    <td>${formatNumber(value.price)} ${value.currency == "usd" ? "$":"L" }</td>
                    <td>${formatNumber(value.price * value.qty)} ${value.currency == "usd" ? "$":"L" }</td>
                    </tr>
                    <br>
                    `;
            });
        }
        function formatNumber(number) {
            let value = String(number).replace(/[^\d.,]/g, "");
            let parts = value.split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        window.print();
    </script>
</body>
</html>