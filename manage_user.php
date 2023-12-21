//edit還沒做


<!DOCTYPE html>
<html lang="en">
<head>
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

<?php
session_start();

// Check if the user is not logged in or not a manager
if (!isset($_SESSION['isManager']) || $_SESSION['isManager'] !== true) {
    // Redirect non-managers to the login page
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
	<a href='../manager/manage_usercreate.php'>新增使用者</a> 
   
    <!-- 登入、登出 -->
    <a href='../logout/logout.php' style='float:right;'>登出</a>
    
    
    <!-- 可以加入其他需要的連結 -->
</div>";


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
        <button onclick='editUser({$row['User_Id']})'>編輯</button>
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
    function editUser(userId) {
        // Redirect to manage_useredit.php with the user ID
        window.location.href = 'manage_useredit.php?userId=' + userId;
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
