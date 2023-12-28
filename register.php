<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
    
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

        /* 下拉菜单樣式 */
        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: #ddd;
            color: black;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a.active {
            background-color: #333;
            color: white;
        }

        /* 修正表格樣式 */
       table {
    width: 100%;
    border=1
    border-collapse: collapse;
    margin-top: 20px; /* 調整與按鈕的間距 */
}

th, td {
    border: 0.001px solid #6E7783; /* 調整框線顏色 */
    padding: 8px;
    text-align: left;
}

th {
		background-color: #9db0c9;
		color: 	black;
		}

		.add-button {
		 background-color: 	#354B5E;
		color: white;
		padding: 10px 20px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		font-size: 16px;
		margin: 20px; /* 調整按鈕的外邊距 */
		}



		body {
		background-color: #ced8e4 ; /* 設定整個網頁的背景顏色 */
		margin: 0; /* 移除預設邊距 */
		}
		.register {
		background-color: #feeba8;
		color: #3e3e3e; 
		padding: 3px 6px; /* 調整按鈕的大小 */
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		font-size: 18.5px;
		margin: 0.01px; /* 調整按鈕的外邊距 */
		}

		.register:hover {
		background-color: #4E5563; /* 在:hover時改變的背景顏色 */
		}

		
		
</style>
</head>
<body>
<div class="navbar">
    <a href="login.php">圖書館座位預約紀錄</a>
    
    
    <!-- 登入、登出 -->
    <a href="login.php" style="float:right;">登入</a>
   
    <!-- 可以加入其他需要的連結 -->
</div>
<?php
session_start();
$mes = '';
$con = new mysqli("localhost", "root", "ccl5266ccl", "圖書館座位預約系統");

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
        $newuser = "INSERT INTO user (User_Account, User_Password, isManager) 
        VALUES ('$account', '$password', 0)";
        
        if ($con->query($newuser) === TRUE) {
            echo '<script>alert("註冊成功，將為您導回登入畫面！"); window.location.href = "login.php";</script>';
            exit; // 確保後續的代碼不會被執行
        } else {
            $mes = "Error: " . $newuser . "<br>" . $con->error;
        }

    } else {
        $mes = "該帳號已存在";
    }
}
?>
</div>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <Form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <?php
    for($i=0;$i<5;$i++){
        echo"</br>";
    }
    ?>
    <div align="center">
    <label for="account">帳號 :</label><br>    
    <input type="text" class="form-control" require="require" name="account" placeholder="Account"  style="width:30%;height: 40px;"></div><br>
    <div align="center">
    <label for="password">密碼 :</label><br>       
    <input type="password" class="form-control" require="require" name="password"  placeholder="Password" style="width:30%;height: 40px;"></div><br>
    <div align="center"><button class="register" onclick="window.location.href='login.php'" class="btn btn-primary" type="submit" name="register" style="width:30%;height: 40px;">註冊</button></div>
    </Form>
    <div align="center"><h4><?php echo $mes?></h4></div>


    </div>
</body>
</html>
