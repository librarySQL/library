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
border-collapse: collapse;
margin-top: 20px; /* 調整與按鈕的間距 */
}

th, td {
border: 0.001px solid #6E7783; /* 調整框線顏色 */
padding: 8px;
text-align: left;
}

th {
    background-color: #475F77;
    color: 	white;
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
    background-color: #DCDDD8; /* 設定整個網頁的背景顏色 */
    margin: 0; /* 移除預設邊距 */
    }
    .edit-button {
    background-color: 	#354B5E;
    color: white;
    padding: 3px 6px; /* 調整按鈕的大小 */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 18.5px;
    margin: 0.01px; /* 調整按鈕的外邊距 */
    }

    .edit-button:hover {
    background-color: #4E5563; /* 在:hover時改變的背景顏色 */
    }

    .delete-button {
    background-color: #4F9D9D;
    color: white;
    padding: 3px 6px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18.5px;


    }
    .delete-button:hover {
    background-color: #4E5563; /* 在:hover時改變的背景顏色 */
    }
    .btn-primary {
    background-color: 	#354B5E;
    color: white;
    }


</style>
</head>
<body>
<div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">使用者</button>
            <div class="dropdown-content">
            <a <?php if (!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type'] !== 'manager')) echo 'class="active"'; ?> href="manage_user.php">使用者名單</a>
            <a <?php if (isset($_GET['type']) && $_GET['type'] === 'manager') echo 'class="active"'; ?> href="manage_user.php?type=manager">管理者名單</a>
            <!-- 新增使用者按鈕 -->
            <?php if (!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type'] !== 'manager')) : ?>
            <?php endif; ?>
        </div>
    </div>

        <a href="seatdetail.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
<?php
    $mes = '';
    $con = new mysqli("localhost", "root", "eva65348642","librarydb");

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
        header("Location:seatdetail.php");
        exit(); // 確保之後的代碼不會執行
    } else {
        $mes = "Error: " . $newseat . "<br>" . $con->error;
    }

    } else {
        $mes = "該座位已存在";
    }
}
?>
   <h1 style="text-align: center; margin-top: 20px;">Create A New Seat</h1>
<div class="container" style="width: 700px;margin: 0px auto; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div align="center">
            座位名稱：<input type="text" class="form-control" require="require" name="seatname" placeholder="seatname"  style="width:30%;height: 40px;"><br><br>
            座位樓層：
            <select name="seatfloor" style="width:30%;height: 40px;">
                <option value="1">1樓</option>
                <option value="2">2樓</option>
                <option value="3">3樓</option>
            </select><br><br>
            有無插座：
            <select name="socket" style="width:30%;height: 40px;">
                <option value="有">有</option>
                <option value="無">無</option>
            </select><br><br>
            <button class="btn-primary" type="submit" name="insert" style="width:30%;height: 40px;">新增座位</button>
        </div>
    </form>
    <div align="center"><h4><?php echo $mes?></h4></div>
</div>

</body>
</html>
