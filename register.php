<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
</head>
<body>
<?php
session_start();
$mes = '';
$con = new mysqli("localhost", "root", "eva65348642","librarydb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['register']) && !empty($_POST['account']) && !empty($_POST['password'])) {

    $account = $_POST['account'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM user WHERE User_Account='$account'";
    $result = $con->query($sql);

    $duplicate = false;
    while ($row = $result->fetch_assoc()) {
        if ($account == $row["User_Account"]) {
            $duplicate = true;
            break; // 如果找到重复的用户，直接跳出循环
        }
    }

    if (!$duplicate) {
        $newuser = "INSERT INTO user(User_Account, User_Password)
                        VALUES ('$account', '$password')";

        if ($con->query($newuser) === TRUE) {
            $mes = "新增成功";
        } else {
            $mes = "Error: " . $newuser . "<br>" . $con->error;
        }
    } else {
        $mes = "該帳號已存在";
    }
}
?>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <Form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <?php
    for($i=0;$i<5;$i++){
        echo"</br>";
    }
    ?>
    <div align="center"><input type="text" class="form-control" require="require" name="account" placeholder="Account"  style="width:30%;height: 40px;"></div><br>
    <div align="center"><input type="password" class="form-control" require="require" name="password"  placeholder="Password" style="width:30%;height: 40px;"></div><br>
    <div align="center"><button class="btn btn-primary" type="submit" name="register" style="width:30%;height: 40px;">註冊</button></div>
    </Form>
    <div align="center"><h4><?php echo $mes?></h4></div>


    </div>
</body>
</html>