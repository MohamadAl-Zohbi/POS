 <?php
    include_once '../common/connect.php';
    include_once './checkLogin.php';
    include_once './onlyAdmin.php';
    if (isset($_POST['add_customer'])) {
        $name      = $_POST['name'];
        $address  = $_POST['address'];

        $sql = "INSERT INTO customers (name, address) 
            VALUES (:name,:address)";

        $addCustomer = $db->prepare($sql);
        $addCustomer->bindParam(':name', $name);
        $addCustomer->bindParam(':address', $address);
        if ($addCustomer->execute()) {
            header('Location: customers.php');
        }
    }
    if (isset($_GET['amount'])) {
        $id = $_GET['id'];
        $amount = $_GET['amount'];
        $getDate = $db->prepare("SELECT date FROM data");
        $dataDate;
        if ($getDate->execute()) {
            $getDate = $getDate->fetchAll(PDO::FETCH_ASSOC);
            if (count($getDate)) {
                $dataDate = $getDate[0]['date'];
            }
        }
        date_default_timezone_set('Asia/Beirut');
        $date      = $dataDate . " " . date("H:i:s");
        if (is_numeric($amount) & $amount > 0) {
            $validate = $db->prepare("SELECT balance FROM customers WHERE id=:id");
            $validate->bindParam(':id', $id);
            if ($validate->execute()) {
                $validate = $validate->fetchAll(PDO::FETCH_ASSOC);
                if (count($validate) == 1) {
                    if ((float)$validate[0]['balance'] >= (float)$amount) {
                        $balance = (float)$validate[0]['balance'] - (float)$amount;
                        $pay = $db->prepare("UPDATE customers SET balance = :balance WHERE id = :id");
                        $pay->bindParam(':id', $id);
                        $pay->bindParam(':balance', $balance);

                        $addEvent = $db->prepare("INSERT INTO customer_payment (id,customer_id,date,amount,method,type) 
                        VALUES (null,:customer_id,'$date',:amount,'cash','cash')");

                        $addEvent->bindParam(':customer_id', $id);
                        $addEvent->bindParam(':amount', $amount);
                        if ($pay->execute() & $addEvent->execute()) header('Location: customers.php');
                    } else {
                        echo '<script>alert("يجب ان لا يكون المبلغ اكبر من الحساب")</script>';
                    }
                } else {
                    echo '<script>alert("هذا الزبون غير متوفر")</script>';
                }
            }
        } else {
            echo '<script>alert("الرجاء ادخال رقم")</script>';
        }
    }
    if (isset($_POST['editCustomer'])) {
        $name      = $_POST['name'];
        $address   = $_POST['address'];
        $balance     = $_POST['balance'];
        $id     = $_POST['id'];
        $sql = "UPDATE customers SET name=:name, address=:address, balance=:balance WHERE id = :id";
        $editCustomer = $db->prepare($sql);
        $editCustomer->bindParam(':name', $name);
        $editCustomer->bindParam(':address', $address);
        $editCustomer->bindParam(':balance', $balance);
        $editCustomer->bindParam(':id', $id);
        if ($editCustomer->execute()) {
            header('Location: customers.php');
        }
    }
    $result = $db->prepare("SELECT * FROM customers");
    $data = [];
    if ($result->execute()) {
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    }
    ?>
 <!DOCTYPE html>
 <html lang="ar" dir="rtl">

 <head>
     <meta charset="UTF-8">
     <title>العملاء</title>
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
         <form method="POST" class="mb-4 row g-2">
             <div class="col-md-2"><input type="text" name="name" placeholder="الاسم" class="form-control" required></div>
             <div class="col-md-2"><input type="text" name="address" placeholder="الموقع" class="form-control" required></div>
             <div class="col-md-2"><button type="submit" name="add_customer" class="btn btn-primary w-100">اضافة عميل</button></div>
         </form>
         <table class="table table-bordered table-striped">
             <thead class="table-dark">
                 <tr>
                     <th>Index</th>
                     <th>Name/الاسم</th>
                     <th>address/الموقع</th>
                     <th>balance/المبلغ</th>
                     <th>Action</th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                    foreach ($data as $i => $item) {
                        echo "<tr>";
                        echo '<td>' . $i + 1 . '</td>';
                        echo '<td>' . $item["name"] . '</td>';
                        echo '<td>' . $item["address"] . '</td>';
                        echo '<td>' . number_format($item["balance"], 2, ".", ",") . 'L.L</td>';
                        echo '<td><a data-id="' . $item['id'] . '" class="btn btn-primary btn-sm" onclick="pay(this)">Pay</a> &nbsp;';
                        echo '<a data-name=' . $item["name"] . ' data-id=' . $item['id'] . ' data-address="' . $item["address"] . '" data-balance="' . $item["balance"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCustomerModal" onclick="showDataBeforeEdit(this)">Edit</a>&nbsp;';
                        echo '<a href="customerDetails.php?id='.$item['id'].'" class="btn btn-secondary btn-sm btn-secondary">Details</a></td>';
                        echo "</tr>";
                    }
                    ?>
             </tbody>
         </table>
     </div>
     <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
         <form method="POST">
             <div class="modal-dialog modal-lg">
                 <div class="modal-content">
                     <div class="modal-header">
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         <h5 class="modal-title" id="editCustomerModalLabel">تعديل العميل</h5>
                     </div>
                     <div class="modal-body">
                         <div class="mb-3">
                             <label for="customerID" class="form-label">رقم التعريف</label>
                             <input type="text" name="id" class="form-control" id="customerID" readonly>
                         </div>
                         <div class="mb-3">
                             <label for="customerName" class="form-label">اسم العميل</label>
                             <input type="text" class="form-control" id="customerName" name="name" placeholder="">
                         </div>
                         <div class="mb-3">
                             <label for="customerAddress" class="form-label">الموقع</label>
                             <input type="text" class="form-control" id="customerAddress" name="address" placeholder="">
                         </div>
                         <div class="row">
                             <div class="col-md-6 mb-3">
                                 <label for="customerBalance" class="form-label">المبلغ</label>
                                 <input type="number" step="0.01" class="form-control" id="customerBalance" name="balance" placeholder="">
                                 <label id="editBalanceLabel"></label>
                                 <script>
                                     document.getElementById("customerBalance").addEventListener("input", function(e) {
                                         let value = e.target.value.replace(/\D/g, "");
                                         document.getElementById("editBalanceLabel").innerText = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                     });
                                 </script>
                             </div>
                         </div>
                     </div>
                     <div class="modal-footer">
                         <button type="submit" name="editCustomer" class="btn btn-primary">حفظ التغييرات</button>
                     </div>
                 </div>
             </div>
         </form>
     </div>
 </body>
 <script src="../common/bootstrap.js"></script>
 <script>
     function showDataBeforeEdit(e) {
         let customerID = document.getElementById("customerID");
         let customerName = document.getElementById("customerName");
         let customerAddress = document.getElementById("customerAddress");
         let customerBalance = document.getElementById("customerBalance");

         customerID.value = e.dataset.id
         customerName.value = e.dataset.name
         customerAddress.value = e.dataset.address
         customerBalance.value = e.dataset.balance
     }

     function pay(e) {
         let id = e.dataset.id;
         let amount = prompt("ادخل المبلغ");
         while (isNaN(amount) || amount == "") {
             amount = prompt("ادخل المبلغ");
         }
         if (amount === null) {
             return false;
         }
         let url = "customers.php?id=" + encodeURIComponent(id) + "&amount=" + amount;
         window.location.href = url;
     }
     let urlParams = new URLSearchParams(window.location.search);
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
     }
 </script>

 </html>