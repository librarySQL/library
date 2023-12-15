<?php
session_start();
if (isset($_GET['reservationId']) &&isset($_GET['account']) && isset($_GET['seatName']) && isset($_GET['startTime']) && isset($_GET['endTime']) && isset($_GET['seatFloor']) && isset($_GET['socket'])) {
    // 獲取 GET 參數
    $reservationId = $_GET['reservationId'];
    $useraccount = $_GET['account'];
    $seatname = $_GET['seatName'];
    $starttime = $_GET['startTime'];
    $endtime = $_GET['endTime'];
    $seatfloor = $_GET['seatFloor'];
    $socket = $_GET['socket'];
} else {
    // 如果未傳遞參數，您可以在此處設置默認值或採取其他適當措施
    $useraccount = '';
    $seatname = '';
    $starttime = '';
    $endtime = '';
    $seatfloor = '';
    $socket = '';
}

if (isset($_SESSION['account'])) {
    // 檢查用戶是否登錄
    $useraccount = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    $con = new mysqli("localhost", "root", "eva65348642", "librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
     // 根據 Reservation ID 從資料庫中檢索相關資訊
     $query = "SELECT r.Reservation_Id, u.User_Account, s.Seat_Name, s.Seat_Floor, s.Socket, r.Start_Time, r.End_Time
    FROM reservation r
    JOIN user u ON r.User_Id = u.User_Id
    JOIN seat s ON r.Seat_Id = s.Seat_Id
    WHERE u.User_Account = '$useraccount'";


    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // 獲取預設值
        $useraccount = $row['User_Account'];
        $seatname = $row['Seat_Name'];
        $starttime = $row['Start_Time'];
        $endtime = $row['End_Time'];
        $seatfloor = $row['Seat_Floor'];
        $socket = $row['Socket'];
    } else {
        echo "No reservation found for the given ID and account.";
        exit; // 如果找不到預約，終止程式
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 如果是POST请求，检查是否存在reservationId，并从POST数据中获取其值
        if (isset($_POST['reservationId'])) {
            $reservationId = $_POST['reservationId'];
            
            // 只更新結束時間 (End_Time)
            $end_datetime = date('Y-m-d H:i:s', strtotime($_POST['End_Time']));
            
            $updateQuery = "UPDATE reservation
                SET End_Time = '$end_datetime'
                WHERE Reservation_Id = '$reservationId';";
            
            if (!empty($updateQuery)) {
                $result = $con->query($updateQuery);
            
                if ($result === TRUE && $con->affected_rows > 0) { 
                    ?>
                    <script language="javascript">
                        alert('預約資料修改完成！');
                        location.href="reservation.php";
                    </script>
                    <?php 
                } else {
                    echo "Error updating record: " . $con->error;
                }
            } else {
                echo "Update query is empty or invalid.";
            }
        } else {
            echo "Reservation ID not found in POST data.";
        }
    } 
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>圖書館座位預約系統</title>
    <!-- 樣式 -->
</head>
<body>
    <!-- Navbar -->
    <!-- ...（其他程式碼） -->

    <!-- 表單 -->
    <h1>Edit Reservation Data</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- 隱藏的 User_Id 欄位 
    <input type="hidden" name="User_Id" value="<?php echo $row['User_Id']; ?>">
    -->
    <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">
    <!-- 帳號 -->
    <label for="User_Account">帳號：</label>
    <input type="text" id="User_Account" name="User_Account" value="<?php echo $useraccount; ?>" readonly><br><br>
    
    <!-- 座位編號 -->
    <label for="Seat_Name">座位編號:</label>
    <input type="text" id="Seat_Name" name="Seat_Name" value="<?php echo $seatname; ?>" readonly><br><br>
    
    <!-- 開始時間 -->
    <label for="Start_Time">開始時間:</label>
    <input type="datetime-local" id="Start_Time" name="Start_Time" value="<?php echo $starttime; ?>" readonly><br><br>

    <!-- 結束時間 -->
    <label for="End_Time">結束時間:</label>
    <input type="datetime-local" id="End_Time" name="End_Time" value="<?php echo $endtime; ?>" required><br><br>

    <!-- 座位樓層 -->
    <label for="Seat_Floor">座位樓層:</label>
    <input type="text" id="Seat_Floor" name="Seat_Floor" value="<?php echo $seatfloor; ?>" readonly><br><br>

    <!-- 插座 -->
    <label for="Socket">插座:</label>
    <input type="text" id="Socket" name="Socket" value="<?php echo $socket; ?>" readonly><br><br>

    <!-- 提交按鈕 -->
    <input type="submit" value="儲存修改">
</form>
</body>
</html>

