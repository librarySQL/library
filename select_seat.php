<?php
session_start();
$conn = new mysqli("localhost", "root", "ccl5266ccl", "圖書館座位預約系統");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// 检查是否收到 POST 请求并且字段不为空
if (isset($_SESSION['account']) && isset($_POST['starttime']) && isset($_POST['endtime']) && isset($_POST['seatfloor']) && isset($_POST['socket'])) {
    $account=$_SESSION['account'];
    $start_time = $_POST['starttime'];
    $end_time = $_POST['endtime'];
    $seatfloor = $_POST['seatfloor'];
    $socket = $_POST['socket'];
    
    

} else {
    echo "未收到正确的数据";
    // 设置默认值或进行其他处理
    $starttime = '';
    $endtime = '';
    $seatfloor = '';
    $socket = '';
}
// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    // 这里你可以使用 $userId 查询数据库或其他存储来获取用户信息
    // 在这个示例中，我们仅显示 "account 您好！" 的消息
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
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
    <title>可預約座位</title>
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

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="userstatus.php">會員</a>
        <a href="seat.php">座位</a>
        <a href="user_reservation.php">預約紀錄</a>
        <a href="user_new_reservation.php">預約座位</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
        <h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
       
        <!-- 可以加入其他需要的連結 -->
    </div>

    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <h2>可預約座位列表</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- 隱藏的 User_Id 欄位 
    <input type="hidden" name="User_Id" value="<?php echo $row['User_Id']; ?>">
    -->
    <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">
    <!-- 帳號 -->
    
    <input type="hidden" id="User_Account" name="User_Account" value="<?php echo $useraccount; ?>" ><br><br>
    
    <!-- 座位編號 -->
    <label for="seatname">座位編號:</label>
<select id="seatname" name="seatname">
    <?php
    // 检查可用座位数组是否存在
    if (isset($_SESSION['available_seats']) && !empty($_SESSION['available_seats'])) {
        // 显示座位信息
        foreach ($_SESSION['available_seats'] as $seat) {
            echo "<option value='$seat'>$seat</option>";
        }
        // 檢查是否有選擇座位並設置 $seat_name 變數
        if (isset($_POST['seatname'])) {
            $seat_name = $_POST['seatname'];
        } else {
            echo "<option value=''>暫無可預約座位</option>";
        }
    }
    ?>
</select>

<br><br>

    <!-- 開始時間 -->
    <label for="starttime">開始時間:</label>
        <input type="datetime-local" id="starttime" name="starttime" value="<?php echo htmlspecialchars($start_time); ?>" readonly><br><br>

        <label for="endtime">結束時間:</label>
        <input type="datetime-local" id="endtime" name="endtime" value="<?php echo htmlspecialchars($end_time); ?>" readonly><br><br>

        <label for="seatfloor">座位樓層:</label>
        <input type="text" id="seatfloor" name="seatfloor" value="<?php echo htmlspecialchars($seatfloor); ?>" readonly><br><br>

        <label for="socket">插座:</label>
        <input type="text" id="socket" name="socket" value="<?php echo htmlspecialchars($socket); ?>" readonly><br><br>
            <!-- 提交按鈕 -->
    <input type="submit" value="預約座位">
<?php
// 檢查 $seat_name 變數是否已設置
if (isset($seat_name)) {
    $user_query = "SELECT User_Id FROM user WHERE User_Account = '$account'";
    $user_result = $conn->query($user_query);
    
    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['User_Id'];
    
        // 查詢 Seat_Id，根據座位名稱
        $seat_query = "SELECT Seat_Id FROM seat WHERE Seat_Name = '$seat_name'";
        $seat_result = $conn->query($seat_query);
    
        if ($seat_result->num_rows > 0) {
            $seat_row = $seat_result->fetch_assoc();
            $seat_id = $seat_row['Seat_Id'];
    
            // 新增預約資料
            $insert_query = "INSERT INTO reservation (Start_Time, End_Time, User_Id, Seat_Id)
                            VALUES ('$start_time', '$end_time', '$user_id', '$seat_id')";
    
            if ($conn->query($insert_query) === TRUE) {
                echo "<br><br>"."預約新增成功";
            } else {
                echo "發生錯誤: " . $conn->error;
            }
        } else {
            echo "找不到相應的座位名稱";
        }
    } else {
        echo "找不到相應的使用者帳號";
    }
}
?>

    </div>
</body>

</html>
