<?php
include_once '../common/connect.php';
include_once './checkLogin.php';
include_once './onlyAdmin.php';
if (isset($_POST['add_user'])) {
    $username      = $_POST['username'];
    $password      = $_POST['password'];
    $role      = $_POST['role'];
    $sql = "INSERT INTO users (username,password,role) VALUES (:username,:password,:role)";
    $addUser = $db->prepare($sql);
    $addUser->bindParam(':username', $username);
    $addUser->bindParam(':password', $password);
    $addUser->bindParam(':role', $role);
    $addUser->bindParam(':is_freez', $is_freez);
    if ($addUser->execute()) {
        header('Location: users.php');
    }
}

// --- Edit User ---
if (isset($_POST['editUser'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $is_freez = $_POST['is_freez'];
    $sql = "UPDATE users SET username=:username,password=:password,role=:role,is_freez=:is_freez WHERE id = :id";

    $editUser = $db->prepare($sql);
    $editUser->bindParam(':id', $id);
    $editUser->bindParam(':username', $username);
    $editUser->bindParam(':password', $password);
    $editUser->bindParam(':role', $role);
    $editUser->bindParam(':is_freez', $is_freez);
    if ($editUser->execute()) {
        header('Location: users.php');
    }
}


// Select Users 
$users = $db->prepare("SELECT * FROM users");
$usersSelect = [];
if ($users->execute()) {
    while ($rowe = $users->fetch(\PDO::FETCH_BOTH)) {
        array_push($usersSelect, $rowe);
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>المستخدمين</title>
    <link href="../common/bootstrap.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">

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
            <div class="col-md-2"><input type="text" name="username" placeholder="الاسم" class="form-control" required></div>
            <div class="col-md-2"><input type="password" name="password" placeholder="كلمة السر" class="form-control" required></div>
            <!-- <div class="col-md-2"><input type="password" name="barcode" placeholder="تأكيد كلمة السر" class="form-control" required></div> -->
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="admin">Admin</option>
                    <option value="pos">POS</option>
                </select>
            </div>

            <div class="col-md-2"><button type="submit" name="add_user" class="btn btn-primary w-100">اضافة مستخدم</button></div>
        </form>

        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Index</th>
                    <th>Name/الاسم</th>
                    <th>Role/المهام</th>
                    <th>Freez/مفعل</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>




                <?php
                // continue from here
                foreach ($usersSelect as $i => $item) {
                    echo "<tr>";
                    echo '<td>' . $i + 1 . '</td>';
                    echo '<td>' . $item["username"] . '</td>';
                    echo '<td>' . $item["role"] . '</td>';
                    echo '<td>' . $item["is_freez"] . '</td>';

                    // echo '<td><a href="?delete=' . $item['username'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">حذف</a> &nbsp;';
                    echo '<td><a data-id="' . $item["id"] . '" data-username="' . $item["username"] . '" data-password="' . $item["password"] . '" data-role="' . $item["role"] . '" data-is_freez="' . $item["is_freez"] . '" class="btn btn-secondary btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="showDataBeforeEdit(this)">تعديل</a></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <form method="POST">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="editUserModalLabel">تعديل المستخدم</h5>
                    </div>

                    <div class="mb-3" style="text-align: center;">
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <input style="max-width: 500px; margin:auto;text-align:center;" type="text" class="form-control" id="username" name="username" placeholder="اسم المستخدم">
                        <input type="hidden" id="id" name="id">


                        <label for="password" class="form-label">كلمة السر</label>
                        <input style="max-width: 500px; margin:auto;text-align:center;" type="text" class="form-control" id="password" name="password" placeholder="كلمة السر">

                        <label for="role" class="form-label">الرتبة</label>
                        <select id="role" style="max-width: 500px; margin:auto;text-align:center;" name="role" class="form-select form-label">
                            <option value="admin">Admin</option>
                            <option value="pos">POS</option>
                        </select>

                        <label for="is_freez" class="form-label">مفعل</label>
                        <select id="is_freez" style="max-width: 500px; margin:auto;text-align:center;" name="is_freez" class="form-select form-label">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="editUser" class="btn btn-primary">حفظ التغييرات</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</body>
<script src="../common/bootstrap.js"></script>
<script>
    function showDataBeforeEdit(e) {
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        let role = document.getElementById("role");
        let id = document.getElementById("id");
        let is_freez = document.getElementById("is_freez");
        id.value = e.dataset.id
        username.value = e.dataset.username
        password.value = e.dataset.password
        role.value = e.dataset.role
        is_freez.value = e.dataset.is_freez
    }

    let urlParams = new URLSearchParams(window.location.search);

    // Check if "error" exists
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