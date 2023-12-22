<!DOCTYPE html>
<html lang="en">
<head>
    <style>
	 .add-button {
        background-color: #4682B4; 
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 20px;
        cursor: pointer;
        transition-duration: 0.4s;
    }

    .add-button:hover {
        background-color: white;
        color: black;
        border: 1px solid #4CAF50; /* 綠色 */
    }
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

<?php
session_start();

// Check if the user is not logged in or not a manager
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    //$suspention=$_SESSION['suspention'];
    // 这里你可以使用 $userId 查询数据库或其他存储来获取用户信息
    // 在这个示例中，我们仅显示 "account 您好！" 的消息
    //$accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    //$Msg="您以違規".$_SESSION['suspention']."次!";
} else {
    // 如果未找到用户ID，可能需要再次重定向到登录页面或者显示错误信息
    header("Location: login.php");
    exit;
}

$con = new mysqli("localhost", "root", "jenny104408!", "libdb");


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}




$sql = "SELECT * FROM user WHERE isManager=0 OR isManager=1"; // Select non-manager users
$result = $con->query($sql);

echo 
"<div class='navbar'>
    <a href='../manager/manage_user.php'>使用者</a>
	 
   
    <!-- 登入、登出 -->
    <a href='../logout/logout.php' style='float:right;'>登出</a>
    
    
    <!-- 可以加入其他需要的連結 -->
</div>";
echo "<button class='add-button' onclick=\"location.href='../manager/manage_usercreate.php'\" style='float:left; margin: 20px;'>新增使用者</button>";


if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>User_Id</th><th>帳號</th><th>密碼</th><th>抵達時間</th><th>離開時間</th><th>違規次數</th><th>停權</th><th>管理者</th><th></th>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['User_Id']}</td>";
        echo "<td>{$row['User_Account']}</td>";
        echo "<td>{$row['User_Password']}</td>";
        echo "<td>{$row['Arrival_Time']}</td>";
        echo "<td>{$row['Departure_Time']}</td>";
        echo "<td>{$row['Number_of_Violation']}</td>";
        echo "<td>{$row['Suspension']}</td>";
        echo "<td>" . ($row['isManager'] == 1 ? '是' : '否') . "</td>";
        echo "<td>
        <button onclick=\"redirectToEditPage('" . $row['User_Id'] . "', '" . $row['User_Account'] . "', '" . $row['User_Password'] . "', '" . $row['Number_of_Violation'] . "', '" . $row['Suspension']. "', '" . $row['isManager'] . "')\">編輯</button>
        <button onclick=\"redirectToDeletePage('" . $row['User_Id'] . "')\">刪除</button>
        </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}


$con->close();
?>
<script>
    function redirectToEditPage(userId, account, password, numberOfviolation, suspension, isManager) {
            var editPageUrl = 'manage_useredit.php' + '?userId=' + encodeURIComponent(userId) + '&account=' + encodeURIComponent(account) + '&password=' + encodeURIComponent(password) + '&numberOfviolation=' + encodeURIComponent(numberOfviolation) + '&suspension=' + encodeURIComponent(suspension) + '&isManager=' + encodeURIComponent(isManager);
            window.location.href = editPageUrl;
        }
	 function redirectToDeletePage(userId) {
            var deleteConfirmation = confirm('是否刪除此帳號？');
            if (deleteConfirmation) {
                var deletePageUrl = 'manage_userdelete.php' + '?userId=' + encodeURIComponent(userId);
                window.location.href = deletePageUrl;
            } else {
                // User chose to cancel, do nothing
            }
        }
   


    /*function redirectToDeletePage(userId) {
            var deleteConfirmation = confirm('是否要取消此訂單？');
            if (deleteConfirmation) {
                var deletePageUrl = 'reservationdelete.php' + '?userId=' + encodeURIComponent(userId);
                window.location.href = deletePageUrl;
            } else {
                // User chose to cancel, do nothing
            }
        }*/
</script>
</body>
</html>
