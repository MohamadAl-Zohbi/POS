<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './closeDay.php';

$categories = $db->prepare("SELECT * FROM categories");
$categoriesList = [];
if ($categories->execute()) {
    while ($row = $categories->fetch(\PDO::FETCH_ASSOC)) {
        array_push($categoriesList, $row);
    }
}

$productWithCategory = $db->prepare("SELECT * FROM products");
//  WHERE category != ''
$productsCategory = [];
if ($productWithCategory->execute()) {
    while ($row = $productWithCategory->fetch(\PDO::FETCH_ASSOC)) {
        array_push($productsCategory, $row);
    }
}

// get customers
$getCustomers = $db->prepare("SELECT * FROM customers");
//  WHERE category != ''
$customers = [];
if ($getCustomers->execute()) {
    while ($row = $getCustomers->fetch(\PDO::FETCH_ASSOC)) {
        array_push($customers, $row);
    }
}

$getDollar = $db->prepare("SELECT dollar FROM data");
$dollar;
if ($getDollar->execute()) {
    $getDollar = $getDollar->fetchAll(PDO::FETCH_ASSOC);
    if (count($getDollar)) {
        $dollar = $getDollar[0]['dollar'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>POS</title>
    <link href="../common/bootstrap.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">


    <style>
        /* :root {
            --primary: #007bff;
            --primary: #7D3F6E;

            --category: #CE8EB3;
            --category: #ddd;
            --category-text: #000;
            --category-text: #000;
            --secondary: #5fa6f1;
            --secondary: #5fa6f1;
            --bg: #8F608C;
            --bg: #f4f6f8;
            --text: #333;
        } */

        :root {
            --primary: #111827;
            /* Dark Navy */
            --secondary: #1F2937;
            /* Slight lighter dark */
            --accent: #10B981;
            /* Emerald Green */
            --danger: #EF4444;
            /* Red */
            --warning: #F59E0B;
            /* Amber */
            --bg: #0F172A;
            /* Deep Dark Blue */
            --card: #1E293B;
            /* Card background */
            --category: #334155;
            /* Product background */
            --category-text: #F1F5F9;
            /* Light text */
            --text: #E5E7EB;
            /* Main text */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            user-select: none;
            -webkit-user-select: none;
            /* Safari */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* IE/Edge */

        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: var(--primary);
            color: var(--text);
            padding: 15px;
            text-align: center;
            font-size: 22px;
        }

        /* header button {
            background-color: #d0ddea;
        } */

        main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            padding: 20px;
            flex: 1;
        }

        section {
            background: var(--card);
            color: var(--category-text);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            resize: both;
            overflow: auto;
        }

        /* Product area */
        .products h2,
        .cart h2,
        .tables h2 {
            color: var(--text);
            font-size: 18px;
            margin-bottom: 10px;
        }

        .product-list {
            /* display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); */
            /* gap: 10px; */
            height: 300px;
            /* overflow: scroll; */
            overflow-y: scroll;
            overflow-x: clip;
        }

        .product {
            background: var(--category);
            color: var(--category-text);
            padding: 8px 2px;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            margin: 5px 0px;
            height: 150px;
            overflow: hidden;
            width: 200px;
        }

        /* Cart area */
        .product-table {
            border: solid;
            width: 100%;
        }

        .product-row {
            /* display: none; */
            /* background-color: red; */
            /* border-bottom: 10px solid red !important; */
            padding: 100px;
        }

        .product-cell {
            /* display: flex; */
            /* align-items: center; */
        }

        .product-cell.action span {
            width: 70px;
            display: inline-block;
        }

        .product-cell.action input {
            width: 60px;
        }


        .product-cell.action {
            /* display: none; */
        }

        .qtn-button {
            width: 15px;
            /* height: 15px; */
            padding: 0px;
            font-size: 17px;
            margin: 0px;
        }


        /* .cart-items {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .cart-item span {
            font-size: 14px;
            background-color: red;
            padding: 0px;
        }

        .cart-item span:nth-of-type(2) {
            font-size: 14px;
            padding: 0px;
        }

        .cart-item span input {
            font-size: 14px;
            margin: 0px;
            height: 20px;
            width: 40px;
        }

        .remove-btn {
            background: red;
            color: white;
            border: none;
            padding: 2px 6px;
            cursor: pointer;
            border-radius: 4px;
            width: 30px;
        } */
        .remove-btn {
            background: red;
            color: var(--text);
            border: none;
            padding: 2px 6px;
            cursor: pointer;
            border-radius: 4px;
            width: 20px;
        }

        /* Controls */
        input[type=text],
        select {
            width: 100%;
            padding: 6px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px;
            margin-top: 5px;
            width: 100%;
            background: var(--primary);
            color: var(--text);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        button:hover {
            background: var(--secondary);
            transform: scale(1.03);
            transition: 0.2s;
        }

        .status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        /* Tables */
        .tables {
            margin-top: 20px;
        }

        .table-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 5px;
        }

        .table {
            background: var(--accent);
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
        }

        .table.active {
            background: var(--primary);
            color: var(--text);
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: var(--text);
            padding: 8px;
        }


        /* button:hover {
            background-color: blue !important;
            box-shadow: 1px 1px 0px 1px #007bff !important;
        } */

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal {
            background-color: var(--primary);
            padding: 20px 30px;
            border-radius: 8px;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .modal h2 {
            margin-bottom: 15px;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-yes {
            background-color: #3498db;
            color: var(--text);
        }

        .btn-no {
            background-color: #e74c3c;
            color: var(--text);
        }

        .btn-yes:hover {
            background-color: #2980b9;
        }

        .btn-no:hover {
            background-color: #c0392b;
        }





        .bold {
            font-weight: bold;
            text-decoration: underline;
        }

        .unactive {
            opacity: 0.5;
        }

        .hidden {
            display: none;
        }

        #salesPopup {
            position: absolute;
            width: 90%;
            right: 5%;
            box-shadow: 1px 1px 100px 100px black;
            z-index: 1000;
        }

        /*  modal */
        /* Button style */
        .btn {
            padding: 12px 20px;
            background: #4b7bec;
            color: var(--text);
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:hover {
            background: var(--primary);
        }

        /* Popup background */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease forwards;
        }

        /* Popup box */
        .popup-content {
            width: 300px;
            background: rgba(255, 255, 255, 0.2);
            padding: 20px 25px;
            border-radius: 15px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.4s ease forwards;
            text-align: center;
            color: var(--text);
        }

        /* Options */
        .options {
            margin: 15px 0;
        }

        .option {
            background: rgba(255, 255, 255, 0.25);
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .option:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-3px);
        }

        /* Close button */
        .close-btn {
            margin-top: 10px;
            padding: 10px 16px;
            background: #ff3f34;
            border: none;
            color: var(--text);
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s;
        }

        .close-btn:hover {
            background: #d63031;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<script>
    let cart = {};
    let customerId = "";
</script>

<body>
    <!-- Button trigger modal -->
    <!-- <button id="openPopup" class="btn">Select Option</button> -->

    <!-- Modal -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Select a Customer</h2>

            <div class="options">
                <?php
                foreach ($customers as $i => $item) {
                    echo '<div class="option" onclick="selectOption(' . $item['id'] . ',`' . $item['name'] . '`)">' . $item['name'] . '</div>';
                }
                ?>
                <!-- <div class="option" onclick="selectOption('Option 1')">Option 1</div>
                <div class="option" onclick="selectOption('Option 2')">Option 2</div>
                <div class="option" onclick="selectOption('Option 3')">Option 3</div> -->
            </div>

            <button id="closePopup" class="close-btn">Close</button>
        </div>
    </div>

    <!-- <p id="result"></p> -->

    <main>
        <section class="products">
            <h2>Products</h2>
            <input type="text" id="barcode" placeholder="Add product by barcode">
            <select style="text-align: center;" class="form-select" id="category" name="category">
                <option value="all">All</option>
                <?php
                foreach ($categoriesList as $i => $item) {
                    echo '<option value="' . $item["name"] . '">' . $item["name"] . '</option>';
                }
                ?>
            </select>


            <div class="product-list" id="product-list">

            </div>


            <div class="tables">
                <h2>Tables</h2>
                <div class="table-list" id="table-list">
                    <div class="table">1</div>
                    <div class="table">2</div>
                    <div class="table">3</div>
                    <div class="table">4</div>
                </div>
                <button onclick="save()">Save</button>
                <button onclick="if(confirm('Are you sure?')){handleYes() }">Clear All Tables</button>

            </div>
        </section>

        <section class="cart" style="width:600px;">
            <h2>Cart</h2>
            <table class="cart-items product-table" id="cart-items">

            </table>
            <div class="status">
                <span id="total">Total: 0</span>
            </div>

            <p id="result"></p>

            <!-- <input type="text" id="search-invoice" placeholder="Search invoice by ID"> -->
            <button onclick="print()">Print</button>
            <button onclick="addItems()">Pay</button>
            <button onclick="print(),addItems()">Pay & Print</button>
            <button
                onclick="openSalesPopup()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                🧾 View Previous Sales
            </button>
            <button id="openPopup" class=" btn-danger">unpaid</button>
            <!-- <button class=" btn-danger" onclick="print()">unpaid</button> -->
            <button onclick="cancelInvoice()">Cancel</button>
            <button onclick="location.replace('./logout.php')" style="color: #CE8EB3; ">LogOut</button>
        </section>
    </main>

    <footer>
        <!-- &copy; 2025 My POS System -->
        <div id="timer"></div>

    </footer>


    <script>
        let total;
        const usd = <?php echo $dollar; ?>


        // let selectedTable = null;

        // Add by clicking product


        // Add by barcode (simulate adding first product)


        function addToCart(name, price, currency, id) {
            if (cart[name]) cart[name].qty += 1;
            else cart[name] = {
                price,
                qty: 1,
                currency,
                id
            };
            updateCart();
        }

        function removeFromCart(name, price) {
            if (cart[name].qty > 1) cart[name].qty -= 1;
            updateCart();
        }



        function updateCart() {
            let itemsDiv = document.getElementById('cart-items');
            itemsDiv.innerHTML = `<tr class="product-row">
        
            <td class="product-cell">
                
            </td>    
            <td class="product-cell action bold">name</td>
            <td class="product-cell action bold">quantity</td>
            <td class="product-cell action bold">price</td>
            <td class="product-cell action bold">total</td>
            <td class="product-cell action bold"></td>
                   
        </tr>`;

            // the main price of the dollar
            // let usd = 89000;
            total = 0;
            for (let name in cart) {
                let item = cart[name];
                let itemTotal = item.qty * item.price;

                if (item.currency == "lbp") {
                    total += itemTotal;
                } else if (item.currency == "usd") {
                    total += (itemTotal * usd);
                }

                let currency = item.currency;
                let div = document.createElement('tr');
                div.className = 'product-row';
                div.innerHTML =
                    `
        
            <td class="product-cell">
                <button onclick="addToCart('${name}',${item.price},'${item.currency}',${item.id})" class="qtn-button">+</button>
                <button onclick="removeFromCart('${name}',${item.price})" class="qtn-button">-</button>
            </td>    
            <td class="product-cell action">${truncate(name,10)} x  </td>
            <td class="product-cell action"><input onchange="editQty('${name}',this.value)" value="${item.qty}"/></td>
            <td class="product-cell action"><span>${currency == "usd" ? "$":"L" }<input onchange="editPrice('${name}',this.value)" type="number" value="${item.price.toFixed(2)}"/></span></td>
            <td class="product-cell action">${formatNumber(itemTotal)} ${currency == "usd" ? "$":"L" }</td>
            <td class="product-cell action"><button class="remove-btn" onclick="removeItem('${name}')">

                   X
                   
                   </button></td>
                   <br><br><br>
        `;
                itemsDiv.appendChild(div);
            }

            if (total === 0) itemsDiv.innerHTML = '<p style=" text-align:center;">Cart is empty</p>';
            document.getElementById('total').innerHTML = 'L.L ' + formatNumber(total.toFixed(2)) + '<br>$$ ' + formatNumber((total / usd).toFixed(2));
        }

        function removeItem(name) {
            delete cart[name];
            updateCart();
        }

        // Tables
        document.querySelectorAll('.table').forEach(t => {
            t.addEventListener('click', () => {
                document.querySelectorAll('.table').forEach(tb => tb.classList.remove('active'));
                t.classList.add('active');
                // selectedTable = t.textContent;
                setDataInwindows();
            });

        });

        // Dummy save/print
        function save() {
            let index = 0;
            document.querySelectorAll('.table').forEach((tb) => {
                index++;
                if (tb.classList.value.includes('active')) {
                    let data = JSON.stringify(cart);
                    localStorage.setItem('window' + index, data);
                }

            });
            alert('saved!!')


        }

        function print() {
            let myWindow = window.open("../common/print.php?data=" + JSON.stringify(cart) + "&dollar=" + usd + "&total=" + total, "_blank", "width=416,height=400,left=200,top=100");
        }

        function editPrice(name, price) {
            cart[name].price = parseFloat(price);
            updateCart();
        }

        function setDataInwindows() {

            let index = 0;
            document.querySelectorAll('.table').forEach((tb) => {
                index++;

                if (tb.classList.value.includes('active')) {
                    let data;
                    if (data = localStorage.getItem('window' + index)) {
                        cart = JSON.parse(data);
                        updateCart();
                    } else {
                        cart = {};
                        updateCart();
                    }
                }

            });

        }

        function editQty(name, qty) {
            cart[name].qty = parseInt(qty);
            updateCart()
        }


        // function openDialog() {
        //     document.getElementById("dialog").style.display = "flex";
        // }

        // function closeDialog() {
        //     document.getElementById("dialog").style.display = "none";
        // }

        function handleYes() {
            localStorage.clear();
            updateCart();
            alert("تم تفريغ الطاولات");
            location.reload();
        }

        function cancelInvoice() {
            cart = {};
            customerId = "";
            document.getElementById("result").innerText = "";
            let index = 0;
            document.querySelectorAll('.table').forEach((tb) => {
                index++;
                if (tb.classList.value.includes('active')) {
                    localStorage.removeItem("window" + index);
                }
            });
            updateCart()
        }
        onload = () => {
            document.getElementById("barcode").focus();
            document.querySelectorAll('.table')[0].classList.add('active');
            setDataInwindows();
        }

        function truncate(str, length = 10) {
            return str.length > length ? str.substring(0, length) + ".." : str;
        }
        let productsWithCategory = <?php echo json_encode($productsCategory); ?>;



        if (document.getElementById("category").value == "all") {
            productsWithCategory.forEach(item => {
                document.getElementById("product-list").innerHTML +=
                    `
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-id="${item['id']}" data-price="${item['price']}">${item['name']}<br>${item['price']}
                <div>
                    ${item['img'] ? "<img src='../uploads/"+item['img']+"' width='100' height='100'/>":""}
                </div>
                </div>
                `;
            });
        }


        // add item by barcode using the laser code
        document.getElementById('barcode').addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                let i = 0;
                productsWithCategory.forEach(item => {
                    if (item['barcode'] == e.target.value) {
                        addToCart(item['name'], item['price'], item['currency'], item['id']);
                        i++;
                    }
                });
                if (i == 0) {
                    alert("لم يتم العثور على المنتج");
                }
                i = 0;
                e.target.value = '';
            }
        });

        document.getElementById("category").addEventListener("change", () => {
            document.getElementById("product-list").innerHTML = "";
            let categoryValue = document.getElementById("category").value;
            if (categoryValue == "all") {
                productsWithCategory.forEach(item => {
                    document.getElementById("product-list").innerHTML +=
                        `
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-id="${item['id']}" data-price="${item['price']}">${item['name']}<br>${item['price']}</div>
                `;
                });
                document.querySelectorAll('.product').forEach(prod => {
                    prod.addEventListener('click', () => {
                        addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency, prod.dataset.id);
                        document.getElementById("barcode").focus();
                    });
                });
                return true;
            }
            let printProductCategoryInTheCart = productsWithCategory.filter(product => product.category == categoryValue);
            printProductCategoryInTheCart.forEach(item => {
                document.getElementById("product-list").innerHTML +=
                    `
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-id="${item['id']}" data-price="${item['price']}">${item['name']}<br>${item['price']}</div>
                `;
            });

            document.querySelectorAll('.product').forEach(prod => {
                prod.addEventListener('click', () => {
                    addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency, prod.dataset.id);
                    document.getElementById("barcode").focus();
                });
            });
        });

        document.querySelectorAll('.product').forEach(prod => {
            prod.addEventListener('click', () => {
                addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency, prod.dataset.id);
                document.getElementById("barcode").focus();
            });
        });

        function openSalesPopup() {
            window.open('../common/previousSales.php', "_blank", "width=416,height=400,left=200,top=100");
        }

        function formatNumber(number) {
            // convert to string and keep only digits, commas, and dots
            let value = String(number).replace(/[^\d.,]/g, "");

            // format with thousands separator (only on the integer part)
            let parts = value.split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return parts.join(".");
        }
    </script>
    <!-- pop up window -->



    <script src="../common/bootstrap.js"></script>
    <script src="../api/posApi.js"></script>

    <!-- modal -->
    <script>
        let popup = document.getElementById("popup");
        let openPopup = document.getElementById("openPopup");
        let closePopup = document.getElementById("closePopup");
        let result = document.getElementById("result");

        openPopup.onclick = () => popup.style.display = "flex";
        closePopup.onclick = () => popup.style.display = "none";

        function selectOption(id, name) {
            result.innerText = "You selected: " + name;
            customerId = id;
            popup.style.display = "none";
        }



        function startTimer() {
            const timerEl = document.getElementById("timer");

            const interval = setInterval(() => {
                const now = new Date();

                // Next day at 00:00:00
                const nextDay = new Date(now);
                nextDay.setHours(24, 0, 0, 0);

                const diffMs = nextDay - now;

                // If day already changed
                if (diffMs <= 0) {
                    timerEl.innerHTML = "⛔ Day ended";
                    clearInterval(interval);
                    location.reload();
                    return;
                }

                // Show timer only in last 5 minutes
                if (diffMs <= 5 * 60 * 1000) {
                    if (diffMs <= 1 * 60 * 1000) {
                        alert("سيتم اعادة تحميل الصفحة خلال دقيقة الرجاء اغلاق الفاتورة")
                    }
                    const minutes = Math.floor(diffMs / 60000);
                    const seconds = Math.floor((diffMs % 60000) / 1000);

                    timerEl.innerHTML = `
                    ⏳ Time left: 
                    <strong>${minutes}:${seconds.toString().padStart(2, "0")}</strong>
                `;

                    // 👉 your action (example)
                    if (minutes === 0 && seconds === 0) {
                        console.log("Expired");
                    }
                } else {
                    timerEl.innerHTML = ""; // hide before last 5 minutes
                }

            }, 1000);
        }

        startTimer();
    </script>
</body>

</html>