<?php
session_start();
$mes = '';
$con = new mysqli("localhost", "root", "eva65348642", "librarydb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['search']) && !empty($_POST['starttime']) && !empty($_POST['endtime']) && !empty($_POST['seatfloor']) && !empty($_POST['socket'])) {

    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $seatfloor = $_POST['seatfloor'];
    $socket = $_POST['socket'];

    // 查询预订了的座位
    $reserved_seats = $con->query("SELECT Seat_Id FROM Reservation WHERE (Start_Time <= '$starttime' AND End_Time >= '$endtime')");

    $reserved_seat_ids = array();
    while ($row = mysqli_fetch_array($reserved_seats)) {
        $reserved_seat_ids[] = $row['Seat_Id'];
    }

    // 查询可用座位
    $available_seats = $con->query("SELECT * FROM Seat WHERE Seat_Floor = '$seatfloor' AND Socket = '$socket'");

    $available_seat_names = array();
    while ($row = mysqli_fetch_array($available_seats)) {
        if (!in_array($row['Seat_Id'], $reserved_seat_ids)) {
            $available_seat_names[] = $row['Seat_Name'];
        }
    }

    if (empty($available_seat_names)) {
        $mes = "暫無可預約座位";
    } else {
        $_SESSION['available_seats'] = $available_seat_names;
        header("Location: ../seat/select_seat.php");
        exit;
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
</head>
<body> 
     <div class="navbar">
                <a href="user/userstatus.php">會員狀態</a>
                <a href="seat/seat.php">所有座位</a>
                <a href="reservation/reservation.php">預約紀錄</a>
                <!-- 登入、登出 -->
                <a href="logout.html" style="float:right;">登出</a>
                <!-- 可以加入其他需要的連結 -->
            </div>
    <br><br>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
        <Form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          
            <div align="center"><input type="datetime-local" class="form-control" require="require" name="starttime" placeholder="starttime"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="datetime-local" class="form-control" require="require" name="endtime" placeholder="endtime"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="text" class="form-control" require="require" name="seatfloor" placeholder="seatfloor"  style="width:30%;height: 40px;"></div><br>
            <div align="center"><input type="text" class="form-control" require="require" name="socket"  placeholder="socket" style="width:30%;height: 40px;"></div><br>
            <div align="center"><button class="btn btn-primary" type="submit" name="search" style="width:30%;height: 40px;">查詢可預約座位</button></div>
        </Form>
        <div align="center"><h4><?php echo $mes?></h4></div>
    </div>
</body>
</html>