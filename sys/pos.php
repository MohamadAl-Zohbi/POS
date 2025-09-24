<?php
include_once '../common/connect.php';
include_once './check.php';





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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modern POS System</title>
    <link href="../common/bootstrap.css" rel="stylesheet">

    <style>
        :root {
            --primary: #007bff;
            --category: #ddd;
            --category-text: #000;
            --secondary: #5fa6f1;
            --bg: #f4f6f8;
            --text: #333;
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
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 22px;
        }

        header button {
            background-color: #d0ddea;
        }

        main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            padding: 20px;
            flex: 1;
        }

        section {
            background: white;
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
            color: var(--primary);
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
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            margin: 5px 0px;
            /* height: 100px; */
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
            color: white;
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
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
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
            background: #ddd;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
        }

        .table.active {
            background: var(--primary);
            color: white;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 8px;
        }


        button:hover {
            background-color: blue !important;
            box-shadow: 1px 1px 0px 1px #007bff !important;

        }

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
            background-color: black;
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
            color: white;
        }

        .btn-no {
            background-color: #e74c3c;
            color: white;
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
    </style>
</head>

<body>
    <div class="modal-overlay" id="dialog">
        <div class="modal">
            <h2>Are you sure?</h2>
            <!-- <p>This action cannot be undone.</p> -->
            <div class="modal-buttons">
                <button class="btn-yes" onclick="handleYes()">Yes</button>
                &nbsp;
                <button class="btn-no" onclick="closeDialog()">No</button>
            </div>
        </div>
    </div>
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
                <!-- <div class="product" data-name="🍎 die32jfio23 foip45jpoti gj4poji op2ig jj" data-price="2">🍎 Appdhwqehfui32ji o4fj4iojfp5o34j gfpo4if4jpfo4ole<br>$2</div>
                <div class="product" data-name="🥛 Milk" data-price="1.5">🥛 Milk<br>$1.5</div>
                <div class="product" data-name="🍞 Bread" data-price="1.2">🍞 Bread<br>$1.2</div>
                <div class="product" data-name="🥤 Soda" data-price="1">🥤 Soda<br>$1</div> -->
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
                <button onclick="openDialog()" style="background-color:red;">Clear All Tables</button>

            </div>
        </section>

        <section class="cart" style="width:600px;">
            <h2>Cart</h2>
            <table class="cart-items product-table" id="cart-items">

            </table>
            <div class="status">
                <label><input type="checkbox" id="paid"> Paid</label>
                <span id="total">Total: 0</span>
            </div>
            <input type="text" id="search-invoice" placeholder="Search invoice by ID">
            <button onclick="print()">Print</button>
            <button class="unactive" onclick="print()">Save & Print</button>
            <button onclick="print()">Pay</button>
            <button class="unactive" onclick="print()">unpaid</button>
            <button onclick="cancelInvoice()">Cancel</button>
        </section>
    </main>

    <footer>&copy; 2025 My POS System</footer>

    <script>
        let cart = {};
        // let selectedTable = null;

        // Add by clicking product


        // Add by barcode (simulate adding first product)


        function addToCart(name, price, currency) {
            if (cart[name]) cart[name].qty += 1;
            else cart[name] = {
                price,
                qty: 1,
                currency
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
            let total = 0;
            for (let name in cart) {
                let item = cart[name];
                let itemTotal = item.qty * item.price;
                total += itemTotal;
                let currency = item.currency;
                let div = document.createElement('tr');
                div.className = 'product-row';
                div.innerHTML =
                    `
        
            <td class="product-cell">
                <button onclick="addToCart('${name}',${item.price})" class="qtn-button">+</button>
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

            // we are here we should make the total with the usd and LBP
            if (total === 0) itemsDiv.innerHTML = '<p style="color:#777; text-align:center;">Cart is empty</p>';
            document.getElementById('total').textContent = 'Total: $' + total.toFixed(2);
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
            let myWindow = window.open("../common/print.php", "_blank", "width=600,height=400,left=200,top=100");
            //    myWindow.document.write(``);


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


        function openDialog() {
            document.getElementById("dialog").style.display = "flex";
        }

        function closeDialog() {
            document.getElementById("dialog").style.display = "none";
        }

        function handleYes() {
            closeDialog();
            console.log("You clicked Yes!");
            localStorage.clear();
            // cart = {};
            updateCart();
            alert("تم تفريغ الطاولات");
            location.reload()
        }

        function cancelInvoice() {
            cart = {}
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
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-price="${item['price']}">${item['name']}<br>${item['price']}</div>
                `;
            });
        }


        // add item by barcode using the laser code
        document.getElementById('barcode').addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                let i = 0;
                productsWithCategory.forEach(item => {
                    if (item['name'] == e.target.value) {
                        addToCart(item['name'], item['price'], item['currency']);
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
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-price="${item['price']}">${item['name']}<br>${item['price']}</div>
                `;
                });
                document.querySelectorAll('.product').forEach(prod => {
                    prod.addEventListener('click', () => {
                        addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency);
                    });
                });
                return true;
            }
            let printProductCategoryInTheCart = productsWithCategory.filter(product => product.category == categoryValue);
            printProductCategoryInTheCart.forEach(item => {
                document.getElementById("product-list").innerHTML +=
                    `
                <div class="product" data-name="${item['name']}" data-currency="${item['currency']}" data-price="${item['price']}">${item['name']}<br>${item['price']}</div>
                `;
            });

            document.querySelectorAll('.product').forEach(prod => {
                prod.addEventListener('click', () => {
                    addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency);
                });
            });
        });

        document.querySelectorAll('.product').forEach(prod => {
            prod.addEventListener('click', () => {
                addToCart(prod.dataset.name, parseFloat(prod.dataset.price), prod.dataset.currency);
            });
        });

        function formatNumber(number) {
            // convert to string and keep only digits, commas, and dots
            let value = String(number).replace(/[^\d.,]/g, "");

            // format with thousands separator (only on the integer part)
            let parts = value.split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return parts.join(".");
        }
    </script>
    <script src="../common/bootstrap.js"></script>
</body>

</html>