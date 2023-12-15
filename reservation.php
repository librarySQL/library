<?php
session_start();


if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account']) ) {
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
    <title>Reservation</title>
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
    </style>
    
</head>
<body>
<div class="navbar">
    <a href="userstatus.php">會員</a>
    <a href="seat.php">座位</a>
    <a href="reservation.php">預約紀錄</a>
    <a href="newreservation.php">預約座位</a>
    <!-- 登入、登出 -->
    <a href="logout.php" style="float:right;">登出</a>
    <h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
   
    <!-- 可以加入其他需要的連結 -->
</div>
<?php
//session_start();
$con = new mysqli("localhost", "root", "eva65348642", "librarydb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$useraccount = $_SESSION['account'];

$query = "SELECT r.Reservation_Id, u.User_Account, s.Seat_Name, s.Seat_Floor, s.Socket, r.Start_Time, r.End_Time
    FROM reservation r
    JOIN user u ON r.User_Id = u.User_Id
    JOIN seat s ON r.Seat_Id = s.Seat_Id
    WHERE u.User_Account = '$useraccount'";


$test = $con->query($query);
echo "<table>
    <tr>
        <th>使用者</th>
        <th>座位編號</th>
        <th>開始時間</th>
        <th>結束時間</th> 
        <th>座位樓層</th>
        <th>插座</th>
        <th></th>
    </tr>";

    while ($row = mysqli_fetch_array($test)) {
        echo "<tr>";
        echo "<td>" . $row['User_Account'] . "</td>";
        echo "<td>" . $row['Seat_Name'] . "</td>";
        echo "<td>" . $row['Start_Time'] . "</td>";
        echo "<td>" . $row['End_Time'] . "</td>"; 
        echo "<td>" . $row['Seat_Floor'] . "</td>";
        echo "<td>" . $row['Socket'] . "</td>";
        echo "<td>
        <button onclick=\"redirectToEditPage('" . $row['Reservation_Id'] . "', '" . $row['User_Account'] . "', '" . $row['Seat_Name'] . "', '" . $row['Start_Time'] . "', '" . $row['End_Time'] . "', '" . $row['Seat_Floor'] . "', '" . $row['Socket'] . "')\">編輯</button>
        <button onclick=\"redirectToDeletePage('" . $row['Reservation_Id'] . "')\">取消預約</button>
              </td>";
        echo "</tr>";
    }
echo "</table>";

echo "<script>

                function redirectToEditPage(reservationId,account, seatName, startTime, endTime, seatFloor, socket) {
                    
                    var editPageUrl = 'edit_reservation.php' + '?reservationId=' + encodeURIComponent(reservationId) + '&account=' + encodeURIComponent(account) + '&seatName=' + encodeURIComponent(seatName) + '&startTime=' + encodeURIComponent(startTime) + '&endTime=' + encodeURIComponent(endTime) + '&seatFloor=' + encodeURIComponent(seatFloor) + '&socket=' + encodeURIComponent(socket);
                    
                    
                    window.location.href = editPageUrl;
                }


                function redirectToDeletePage(reservationId) {
                    var deletePageUrl = 'delete_reservation.php' + '?reservationId=' + encodeURIComponent(reservationId);
                    
                    // 使用 JavaScript 跳轉到取消預約頁面
                    window.location.href = deletePageUrl;
                }
              </script>";
              

$con->close();
?>
</body>
</html>
