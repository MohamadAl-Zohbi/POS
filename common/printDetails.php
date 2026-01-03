<?php
include_once '../common/connect.php';
// include_once './checkLogin.php';
// include_once './onlyAdmin.php';


$factureLines = $db->prepare("SELECT products.name,sale_items.quantity,sale_items.unit_price,sale_items.currency,sales.date,sales.total_amount_lbp,sales.total_amount_usd FROM sale_items,sales,products WHERE sale_items.sale_id=sales.id AND sale_items.sale_id=" . $_GET['id'] . " AND
 products.id=sale_items.product_id ;");



$factureSelect = [];
if ($factureLines->execute()) {
    while ($rowe = $factureLines->fetch(\PDO::FETCH_BOTH)) {
        array_push($factureSelect, $rowe);
    }
}


$getData = $db->prepare("SELECT * FROM data");
$data;
if ($getData->execute()) {
    $getData = $getData->fetchAll(PDO::FETCH_ASSOC);
    $data = $getData[0];
}

?>
<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <!-- <title>POS Facture</title> -->
    <style>
        /* Page setup for 80mm thermal printer */
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
            text-align: center;
        }

        th,
        td {
            font-size: 12px;
            text-align: center;

            padding: 2px 1px;
            max-width: 100px;
        }

        th {
            border-bottom: 1px dashed #000;
        }

        tr {
            margin-bottom: 20px;
            /* border-bottom: 1px dashed black; */
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

        /* For print only */
        /* @media print {
            body {
                margin: 0;
                padding: 0;
            }
        } */
    </style>
</head>

<body>
    <div style="background-color: white; padding:10px;">
        <div class="header">
            <h2>SuperMarket <?php echo $data['company_name'];?></h2>
            <p><?php echo $data['address'];?></p>
            <p>Tel: <?php echo $data['tel'];?></p>
            <p>Date: <?php echo $factureSelect[0]['date'];?></p>
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
                    <td id="subTotal" style="text-align:right;"></td>
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
    </div>

    <script>
        const lines = <?php echo json_encode($factureSelect); ?>;

        onload = () => {
            // let urlParams = new URLSearchParams(window.location.search);
            // let data = JSON.parse(urlParams.get("data"))
            // let total = JSON.parse(urlParams.get("total"))





            let body = document.getElementById("containar");
            let total_usd;
            let total_lbp;
            Object.entries(lines).forEach(([key, value]) => {
                body.innerHTML +=
                    `
                    <tr>
                    <td>${value.name}</td>
                    <td>${formatNumber(value.quantity)}</td>
                    <td>${formatNumber(value.unit_price)}${value.currency == "usd" ? "$":"L" }</td>
                    <td>${formatNumber(value.unit_price*value.quantity)}${value.currency == "usd" ? "$":"L" }</td>
                    </tr>
                    <br>
                    `;

                    total_lbp = value.total_amount_lbp;
                    total_usd = value.total_amount_usd;


            });
            
                document.getElementById("subTotal").innerHTML = formatNumber(total_lbp) + " L.L<br> " + formatNumber(total_usd) + "$" ;
                document.getElementById("tax").innerHTML = formatNumber(total_lbp * 0.11) + " L.L<br> " + formatNumber(total_usd * 0.11) + "$" ;
                document.getElementById("total").innerHTML = formatNumber((total_lbp * 0.11) + total_lbp) + " L.L<br> " +formatNumber((total_usd * 0.11) + total_usd) + "$";

        }

        function formatNumber(number) {
            // convert to string and keep only digits, commas, and dots
            let value = String(number).replace(/[^\d.,]/g, "");

            // format with thousands separator (only on the integer part)
            let parts = value.split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return parts.join(".");
        }

        // Uncomment if you want it to auto-print
        window.print();
    </script>
</body>

</html>