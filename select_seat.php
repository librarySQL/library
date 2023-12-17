<?php
session_start();
$conn = new mysqli("localhost", "root", "jenny104408!", "libdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['account']) && isset($_POST['starttime']) && isset($_POST['endtime']) && isset($_POST['seatfloor']) && isset($_POST['socket'])) {
    $account = $_SESSION['account'];
    $start_time = $_POST['starttime'];
    $end_time = $_POST['endtime'];
    $seatfloor = $_POST['seatfloor'];
    $socket = $_POST['socket'];
    
    // 檢查是否有選擇座位並設置 $seat_name 變數
    if (isset($_POST['seatname'])) {
        $seat_name = $_POST['seatname'];
    }
} else {
    echo "未收到正确的数据";
    $starttime = '';
    $endtime = '';
    $seatfloor = '';
    $socket = '';
    $seat_name = ''; // 新增這行，初始化 $seat_name
}


if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account'])) {
    $userId = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
} else {
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
    <div class='navbar'>
        <a href='../userstatus.php'>會員</a>
        <a href='../seat/seat.php'>座位</a>
        <a href='../reservation/reservation.php'>預約紀錄</a>
        <a href='../reservation/newreservation.php'>新增預約</a>
        <a href='../logout.php' style='float:right;'>登出</a>
    </div>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
        <h2>可預約座位列表</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">

            <input type="hidden" id="User_Account" name="User_Account" value="<?php echo $useraccount; ?>" ><br><br>

            <label for="seatname">座位編號:</label>
            <select id="seatname" name="seatname">
                <?php
                if (isset($_POST['seatname'])) {
                    $seat_name = $_POST['seatname'];
                }

                $reserved_seats_query = "SELECT Seat_Id FROM reservation";
                $reserved_seats_result = $conn->query($reserved_seats_query);
                $reserved_seats = array();

                if ($reserved_seats_result->num_rows > 0) {
                    while ($row = $reserved_seats_result->fetch_assoc()) {
                        $reserved_seats[] = $row['Seat_Id'];
                    }
                }

                // 在選擇座位的 SQL 查詢中加入預約時間的條件
// 在選擇座位的 SQL 查詢中加入預約時間的條件
$filtered_seats_query = "SELECT s.Seat_Name 
                        FROM seat s
                        LEFT JOIN reservation r ON s.Seat_Id = r.Seat_Id 
                            AND ('$start_time' >= r.Start_Time AND '$start_time' < r.End_Time
                                OR '$end_time' > r.Start_Time AND '$end_time' <= r.End_Time
                                OR r.Start_Time >= '$start_time' AND r.Start_Time < '$end_time'
                                OR r.End_Time > '$start_time' AND r.End_Time <= '$end_time')
                        WHERE s.Seat_Floor = '$seatfloor' AND s.Socket = '$socket'
                            AND r.Seat_Id IS NULL";


$filtered_seats_result = $conn->query($filtered_seats_query);

if ($filtered_seats_result->num_rows > 0) {
    while ($row = $filtered_seats_result->fetch_assoc()) {
        $seat = $row['Seat_Name'];
        // 將選中的座位名稱設置為 selected
        $selected = ($seat == $seat_name) ? 'selected' : '';
        echo "<option value='$seat' $selected>$seat</option>";
    }
} else {
    echo "<option value=''>沒有符合條件的座位</option>";
}


                ?>
            </select>

            <br><br>

            <label for="starttime">開始時間:</label>
            <input type="datetime-local" id="starttime" name="starttime" value="<?php echo htmlspecialchars($start_time); ?>" readonly><br><br>

            <label for="endtime">結束時間:</label>
            <input type="datetime-local" id="endtime" name="endtime" value="<?php echo htmlspecialchars($end_time); ?>" readonly><br><br>

            <label for="seatfloor">座位樓層:</label>
            <input type="text" id="seatfloor" name="seatfloor" value="<?php echo htmlspecialchars($seatfloor); ?>" readonly><br><br>

            <label for="socket">插座:</label>
            <input type="text" id="socket" name="socket" value="<?php echo htmlspecialchars($socket); ?>" readonly><br><br>

            <input type="submit" value="預約座位">
            
            <?php
            if (isset($seat_name)) {
                $user_query = "SELECT User_Id FROM user WHERE User_Account = '$account'";
                $user_result = $conn->query($user_query);
                
                if ($user_result->num_rows > 0) {
                    $user_row = $user_result->fetch_assoc();
                    $user_id = $user_row['User_Id'];
                
                    $seat_query = "SELECT Seat_Id FROM seat WHERE Seat_Name = '$seat_name'";
                    $seat_result = $conn->query($seat_query);
                
                    if ($seat_result->num_rows > 0) {
                        $seat_row = $seat_result->fetch_assoc();
                        $seat_id = $seat_row['Seat_Id'];
                
                        $insert_query = "INSERT INTO reservation (Start_Time, End_Time, User_Id, Seat_Id)
                                        VALUES ('$start_time', '$end_time', '$user_id', '$seat_id')";
                
                        if ($conn->query($insert_query) === TRUE) {
                            echo '<script>alert("您預約成功了！");</script>';
                            echo '<script>window.location.href = "../reservation/newreservation.php";</script>';
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
        </form>
    </div>
</body>

</html>
