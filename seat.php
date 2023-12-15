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
<?php

$con = new mysqli("localhost", "root", "eva65348642", "librarydb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$seat = $con->query("SELECT * FROM seat");

echo "<table>
    <tr>
        <th>座位編號</th>
        <th>座位樓層</th>
        <th>插座</th>
        
    </tr>";

while ($row = mysqli_fetch_array($seat)) {
    echo "<tr>";
    echo "<td>" . $row['Seat_Name'] . "</td>";
    echo "<td>" . $row['Seat_Floor'] . "</td>";
    echo "<td>" . $row['Socket'] . "</td>";
    echo "</tr>";
}

echo "</table>";

$con->close();
?>

</body>
</html>
