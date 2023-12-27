<?php
session_start();

// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    //$Msg="您以違規".$_SESSION['suspention']."次!";
} else {
    // 如果未找到用户ID，可能需要再次重定向到登录页面或者显示错误信息
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座位</title>
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
        
            
   
		<a href="manage_user.php">使用者</a>
        <a href="manage_seat.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
<?php
    $mes = '';
    $con = new mysqli("localhost", "root", "jenny104408!","libdb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_POST['insert']) && !empty($_POST['seatname']) && !empty($_POST['seatfloor']) && !empty($_POST['socket'])) {

    $seatfloor = $_POST['seatfloor']; // 修改字段名为 seatfloor
    $socket = $_POST['socket'];
    $seatname = $_POST['seatname'];

    $sql = "SELECT * FROM seat WHERE Seat_Name='$seatname'";
    $result = $con->query($sql);

    $duplicate = false;
    while ($row = $result->fetch_assoc()) {
        if ($seatname == $row["Seat_Name"]) {
            $duplicate = true;
            break;
        }
    }

    if (!$duplicate) {
        $newseat = "INSERT INTO seat(Seat_Name,Seat_Floor, Socket)
                        VALUES ('$seatname','$seatfloor', '$socket')";

    if ($con->query($newseat) === TRUE) {
        $mes = "新增成功";
        // 將使用者重新導向到 seatdetail.php
        header("Location:manage_seat.php");
        exit(); // 確保之後的代碼不會執行
    } else {
        $mes = "Error: " . $newseat . "<br>" . $con->error;
    }

    } else {
        $mes = "該座位已存在";
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
	
    <div align="center">座位名稱：<input type="text" class="form-control" require="require" name="seatname" placeholder="seatname"  style="width:30%;height: 40px;"></div><br>
    <div align="center">座位樓層：<input type="text" class="form-control" require="require" name="seatfloor" placeholder="seatfloor"  style="width:30%;height: 40px;"></div><br>
    <div align="center">有無插座：<input type="text" class="form-control" require="require" name="socket"  placeholder="socket" style="width:30%;height: 40px;"></div><br>
    <div align="center"><button class="btn-primary" type="submit" name="insert" style="width:30%;height: 40px;">新增座位</button></div>
    </Form>
    <div align="center"><h4><?php echo $mes?></h4></div>


    </div>
</body>
</html>
