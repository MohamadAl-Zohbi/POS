<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #007BFF, #00BFFF);
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: white;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            text-align: center;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #007BFF;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    include_once '../common/connect.php';
    ?>



    <main>
        <div class="login-container">
            <h2>Login</h2>
            <form method="POST" id="form" action="login.php">
                <input id="username" type="text" name="username" placeholder="Username" required />
                <input type="password" name="password" placeholder="Password" autocomplete="section-red shipping street-address" required/>
                <input type="submit" value="Login">
            </form>
        </div>
    </main>
    <script>
        // document.getElementById("form").onsubmit = ()=>{
        //     localStorage.setItem('username',form.username.value);
        //     localStorage.setItem('password',form.password.value);   

        // }
        onload = ()=>{
            document.getElementById("username").focus();
        }
    </script>
</body>

</html>


<!-- <php>  -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $is_defined = $db->prepare('SELECT * FROM users WHERE username=:username and password=:password');
        $is_defined->bindParam(':username', $username);
        $is_defined->bindParam(':password', $password);
        $is_defined->execute();

        $result = $is_defined->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
            session_start();
            $_SESSION['username'] = $username;

            if ($result[0]['role'] == "admin") {
                header('Location: dashboard.php');
            } else {
                header('Location: pos.php');
            }
        } else {
            echo '<script>alert("wrong username or password")</script>';
        }
        exit;
    } else {
        echo '<script>error</script>';
    }
}
?>