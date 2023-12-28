<!--先去db的user資料表加入isManager 型態TINYINT 長度設1          (true=1 false=0)
第38行的ex.php是管理者登入後看到的頁面(還沒做)-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    
    /* Navbar 樣式 */
    .navbar {
        overflow: hidden;
        background-color: #333;
    }

    .navbar a {
        float: left;
        display: block;
        color: white;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .add-button {
        background-color: #3A3A3A;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 16px;
    }
    .register {
        background-color: #4f9d9d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }
    body {
    background-color: #DCDDD8; /* 設定整個網頁的背景顏色 */
    margin: 0; /* 移除預設邊距 */
    }
    .btn-primary {
    background-color: 	#354B5E;
    color: white;
    padding: 3px 6px; /* 調整按鈕的大小 */

    font-size: 14.5px;
    }
    

</style>
</head>
<body>
<div class="navbar">
        
        <a href="login.php">圖書館座位預約紀錄</a>
        
        <!-- 登入、登出 -->
        <a href="register.php" style="float:right;">註冊</a>
		
    </div>
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
            $row = $result->fetch_assoc();

            // Check if the user is a manager
            if ($row['isManager'] == 1) {
                $_SESSION['login'] = true;
                $_SESSION['isManager'] = true;
                $_SESSION['userid'] = $row['User_Id'];
                $_SESSION['account'] = $row['User_Account'];
                $_SESSION['password'] = $row['User_Password'];
                // Redirect to user.php if the user is a manager
                header("Location: manage_user.php");
                exit;
            }
            else if($row['isManager'] == 0){
                $_SESSION['login'] = true;
                $_SESSION['userid'] = $row['User_Id'];
                $_SESSION['account'] = $row['User_Account'];
                $_SESSION['password'] = $row['User_Password'];
                header("Location:user_new_reservation.php");
                exit;
        }
            
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
        <br>
        <div align="center">
        <b><label for="account">帳號 :</label></b><br>    
        <input type="text" class="form-control" name="account" placeholder="Account" style="width:30%;height: 40px;"></div><br>
        <div align="center">
        <b><label for="password">密碼 :</label></b><br>    
        <input type="password" class="form-control" name="password" placeholder="Password" style="width:30%;height: 40px;"></div><br>
        <div align="center"><button class="btn btn-primary" type="submit" name="login" style="width:30%;height: 40px;">Login</button></div>
        <br>
        <div align="center">
            <button class="register" onclick="window.location.href='register.php'" type="button" name="register" style="width:30%;height: 40px;" id="register">Register</button>
        </div>
    </form>
</div>
</body>
</html>
