<?php
include_once './connect.php';
include_once '../sys/checkLogin.php';
$sales = $db->prepare("SELECT * FROM sales WHERE date >= '" . date('Y-m-d') . "' AND date <  '" . date('Y-m-d', strtotime('+1 day')) . "' ORDER BY id DESC;");
$salesSelect = [];
if ($sales->execute()) {
    while ($rowe = $sales->fetch(\PDO::FETCH_BOTH)) {
        array_push($salesSelect, $rowe);
    }
}
?>
<style>
    #salesPopup {
        position: absolute;
        width: 90%;
        right: 5%;
        box-shadow: 1px 1px 100px 100px black;
        z-index: 1000;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
<link href="./bootstrap.css" rel="stylesheet">
<div id="salesPopup" class="hidden">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-4">
        <div class="flex justify-between items-center border-b pb-2 mb-3">
            <h2 class="text-lg font-semibold">🧾 Previous Sales</h2>
        </div>
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-200" id="salesList">
        </div>
        <div class="mt-3 text-right">
        </div>
    </div>
</div>
<script>
    const sales = <?php echo json_encode($salesSelect); ?>;
    const listContainer = document.getElementById("salesList");
    function openSalesPopup() {
        document.getElementById("salesPopup").classList.remove("hidden");
        renderSales();
    }

    function closeSalesPopup() {
        document.getElementById("salesPopup").classList.add("hidden");
    }

    function renderSales() {
        listContainer.innerHTML = ""; // clear previous list
        sales.forEach(sale => {
            const div = document.createElement("div");
            div.className = "m-2 border-bottom cursor-pointer hover:bg-gray-50 transition";
            div.innerHTML = `
        <div class="flex justify-between">
          <div>
            <p class="font-medium">Invoice #${sale.id}</p>
            <p class="text-xs text-gray-500">${sale.date}</p>
          </div>
          <p class="font-bold text-green-600">L.L ${sale.total_amount_lbp.toLocaleString()}</p>
          <p class="font-bold text-green-600">$.$ ${sale.total_amount_usd.toLocaleString()}</p>
        </div>
        <div class="mt-1 text-xs text-gray-500">
          <p>Cashier: ${sale.user_id}</p>
        </div>
      `;
            div.onclick = () => window.open('./printDetails.php?id=' + sale.id, "_blank", "width=416,height=400,left=200,top=100");;
            listContainer.appendChild(div);
        });
    }

    onload = () => {
        openSalesPopup();
    }
</script>
<script src="./bootstrap.js"></script>