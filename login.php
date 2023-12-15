<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<?php
session_start();
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 处理 POST 请求
    $con = new mysqli("localhost", "root", "eva65348642","librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_POST['login']) && !empty($_POST['account']) && !empty($_POST['password'])) {
        $account = $_POST['account'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM user WHERE User_Account='$account' AND User_Password='$password'";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0) {
            $_SESSION['login'] = true;
            $row = $result->fetch_assoc();
            $_SESSION['userid'] = $row['User_Id'];
            $_SESSION['account'] = $row['User_Account'];
            $_SESSION['password'] = $row['User_Password'];
            header("Location: reservation/newreservation.php");
            exit;
        } else {
            $msg = "Wrong account or password!";
        }
    }

    $con->close();
}
?>
   <div class="container">
        <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($msg)) {
                echo "<h4 class='form-signin-heading'>$msg</h4>";
            }
            ?>
            <div align="center"><input type="text" class="form-control" name="account" placeholder="Account" style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="password" class="form-control" name="password" placeholder="Password" style="width:30%;height: 40px;"></div><br>
            <div align="center"><button class="btn btn-primary" type="submit" name="login" style="width:30%;height: 40px;">Login</button></div>
            <div align="center">
            <button onclick="window.location.href='register.php'" type="button" name="register" style="width:30%;height: 40px;" id="register">register</button>
            </div>
        </form>
    </div>
</body>
</html>
