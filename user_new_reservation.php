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

if (isset($_POST['search']) && !empty($_POST['starttime']) && !empty($_POST['endtime']) && !empty($_POST['seatfloor']) && !empty($_POST['socket'])) {

    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $seatfloor = $_POST['seatfloor'];
    $socket = $_POST['socket'];

    // 查询可用座位
    $available_seats_query = "SELECT Seat_Floor,Seat_Name,Socket FROM seat
        WHERE Seat_Id NOT IN (
            SELECT Seat_Id FROM Reservation 
            WHERE (Start_Time <= '$endtime' AND End_Time >= '$starttime')
        ) 
        AND Seat_Floor = '$seatfloor' 
        AND Socket = '$socket'";

    $available_seats_result = $con->query($available_seats_query);

    if ($available_seats_result->num_rows > 0) {
        $available_seat_names = array();
        while ($row = mysqli_fetch_array($available_seats_result)) {
            $available_seat_names[] = $row['Seat_Name'];
        }

        $_SESSION['available_seats'] = $available_seat_names;
        header("Location: select_seat.php");
        exit;
    } else {
        $mes = "暫無可預約座位";
    }
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
     <!-- 其他標頭內容 -->
     <script>
function redirectToSelectSeat() {
    var starttime = document.getElementsByName('starttime')[0].value;
    var endtime = document.getElementsByName('endtime')[0].value;
    var seatfloor = document.getElementsByName('seatfloor')[0].value;
    var socket = document.getElementsByName('socket')[0].value;

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "select_seat.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "starttime");
    hiddenField.setAttribute("value", starttime);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "endtime");
    hiddenField.setAttribute("value", endtime);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "seatfloor");
    hiddenField.setAttribute("value", seatfloor);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "socket");
    hiddenField.setAttribute("value", socket);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}


</script>

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
    <form id="reservationForm" class="form-signin" role="form" onsubmit="redirectToSelectSeat(); return false;">
            <div align="center"><input type="datetime-local" class="form-control" require="require" name="starttime" placeholder="starttime"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="datetime-local" class="form-control" require="require" name="endtime" placeholder="endtime"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="text" class="form-control" require="require" name="seatfloor" placeholder="seatfloor"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="text" class="form-control" require="require" name="socket"  placeholder="socket" style="width:30%;height: 40px;"></div><br>
            <!-- 前面的代码保持不变 -->
            <div align="center">
        <input type="submit" value="查詢可預約座位" style="width:30%;height: 40px;">
    </div>
        </Form>
    </div>
</body>
</html>
