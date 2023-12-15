<?php
session_start();

// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
if (isset($_SESSION['account'])) {
    $useraccount = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';

    $con = new mysqli("localhost", "root", "eva65348642", "librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
    // 添加引號以包裹帳戶名稱
    $user = $con->query("SELECT * FROM user WHERE User_Account='$useraccount'");

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>圖書館座位預約系統</title>
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
        <div class='navbar'>
            <a href='userstatus.php'>會員</a>
            <a href='seat/seat.php'>座位</a>
            <a href='reservation/reservation.php'>預約紀錄</a>
            <a href='reservation/newreservation.php'>新增紀錄</a>
            <!-- 登入、登出 -->
            <a href='logout.php' style='float:right;'>登出</a>
            <h4 style='float:right;'><font color='white'>$accountMessage</font></h4>
           
            <!-- 可以加入其他需要的連結 -->
        </div>";

    echo "<table>
        <tr>
            <th>帳號</th>
            <th>密碼</th>
            <th>抵達時間</th>
            <th>離開時間</th>
            <th>違規次數</th>
            <th>停權</th>
            <th></th>
        </tr>";

        while ($row = mysqli_fetch_array($user)) {
            echo "<tr>";
            echo "<td>" . $row['User_Account'] . "</td>";
            echo "<td>" . $row['User_Password'] . "</td>";
            echo "<td>" . $row['Arrival_Time'] . "</td>";
            echo "<td>" . $row['Departure_Time'] . "</td>";
            echo "<td>" . $row['Suspension'] . "</td>";
            echo "<td>" . $row['Number_of_Violation'] . "</td>";
            echo "<td>
                    <button onclick='redirectToEditPage()'>編輯</button>              
                    <button onclick=\"detailsFunction({$row['User_Id']})\">詳細</button>
                    <button onclick=\"deleteFunction({$row['User_Id']})\">刪除</button>
                  </td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // 添加引號以包裹帳戶名稱
        echo "</body></html>";
        
        // 這裡嵌入 JavaScript 函數
        echo "<script>
                function redirectToEditPage() {
                    // 在這裡更改為您的編輯頁面 URL
                    var editPageUrl = 'user/useredit.php'; // 更改成您的編輯頁面 URL
            
                    // 使用 JavaScript 跳轉到編輯頁面
                    window.location.href = editPageUrl;
                }
              </script>";
    echo "</table>";
    
    echo "</body></html>";

    $con->close();
} else {
    header("Location: login.php");
    exit;
}
?>
