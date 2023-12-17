<?php
session_start();
$con = new mysqli("localhost", "root", "ccl5266ccl", "圖書館座位預約系統");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    //$suspention=$_SESSION['suspention'];
    // 这里你可以使用 $userId 查询数据库或其他存储来获取用户信息
    // 在这个示例中，我们仅显示 "account 您好！" 的消息
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
    <title>新增預約</title>
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
    
    </style>
</head>
<body> 
<div class="navbar">
    <a href="userstatus.php">會員</a>
    <a href="seat.php">座位一覽</a>
    <a href="user_reservation.php">預約紀錄</a>
    <a href="user_new_reservation.php">預約座位</a>
    <a href="search_seat.php">查詢座位</a>
    
    <!-- 登入、登出 -->
    <a href="logout.php" style="float:right;">登出</a>
    <h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
   
    <!-- 可以加入其他需要的連結 -->
</div>

    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <br><br>
<div align="center">
    <label for="seatname">請選擇想查詢的座位編號:</label>
    <br><br>
    <select id="seatname" name="seatname" onchange="fetchReservation()">
        <?php

        // 檢查是否有選擇座位
        if (isset($_POST['seatname']) && !empty($_POST['seatname'])) {
            $selected_seat = $_POST['seatname'];

            // 在選擇座位的 SQL 查詢中加入預約時間的條件
            $filtered_seats_query = "SELECT * FROM seat";
            $filtered_seats_result = $con->query($filtered_seats_query); // 修改此處的 $conn 為 $con

            if ($filtered_seats_result->num_rows > 0) {
                while ($row = $filtered_seats_result->fetch_assoc()) {
                    $seat = $row['Seat_Name'];
                    $selected = ($seat == $seat_name) ? 'selected' : '';
                    echo "<option value='$seat' $selected>$seat</option>";
                }
            } else {
                echo "<option value=''>沒有符合條件的座位</option>";
            }
        }else {
            // 如果未接收到 seatname 參數，可以採取相應的錯誤處理措施
            echo "未接收到座位名稱參數";
        }
        

        ?>
    </select>
</div>

<div style="text-align: center;">
<div id="reservation_info" style="display: inline-block; text-align: center; margin-top: 20px;"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function fetchReservation() {
    var selectedSeat = $("#seatname").val();

    $.ajax({
        type: "POST",
        url: "fetch_reservation.php",
        data: { seatname: selectedSeat },
        success: function(response) {
            $('#reservation_info').html(response);
        }
    });
}
</script>
<br><br>
<div align="center">
    <input type="submit" value="查詢可預約座位" style="width:30%;height: 40px;">
</div>
</form>
</body>
</html>
