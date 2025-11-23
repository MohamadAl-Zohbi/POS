<?php
include_once './connect.php';
include_once '../sys/check.php';


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
    <!-- Popup box -->
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-4">
        <div class="flex justify-between items-center border-b pb-2 mb-3">
            <h2 class="text-lg font-semibold">🧾 Previous Sales</h2>
            <!-- <button onclick="closeSalesPopup()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button> -->
        </div>

        <!-- Scrollable content -->
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-200" id="salesList">
            <!-- Sales will be added here dynamically -->
        </div>

        <!-- Footer -->
        <div class="mt-3 text-right">
            <!-- <button onclick="closeSalesPopup()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded-md">
                Close
            </button> -->
        </div>
    </div>
</div>

<script>
    // let productsWithCategory = php echo json_encode($productsCategory); ?>;

    // const sales = [{
    //         id: 1025,
    //         date: "2025-10-24 19:45",
    //         total: 194250,
    //         items: 5,
    //         cashier: "Mohamad"
    //     },
    //     {
    //         id: 1024,
    //         date: "2025-10-24 18:30",
    //         total: 82000,
    //         items: 3,
    //         cashier: "Ali"
    //     },
    //     {
    //         id: 1023,
    //         date: "2025-10-24 16:15",
    //         total: 320000,
    //         items: 10,
    //         cashier: "Rami"
    //     },
    //     {
    //         id: 1022,
    //         date: "2025-10-24 15:00",
    //         total: 102000,
    //         items: 4,
    //         cashier: "Sara"
    //     },
    //     {
    //         id: 1021,
    //         date: "2025-10-24 14:10",
    //         total: 220000,
    //         items: 7,
    //         cashier: "Youssef"
    //     },
    // ];
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