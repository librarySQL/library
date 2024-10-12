<?php
session_start();

    $con = new mysqli("localhost", "root", "yourpassword", "librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: login.php");
        exit;
    }

    if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    
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
    <title>管理使用者</title>
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

        /* 下拉菜单樣式 */
        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: #ddd;
            color: black;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a.active {
            background-color: #333;
            color: white;
        }

        /* 修正表格樣式 */
       table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px; /* 調整與按鈕的間距 */
}

th, td {
    border: 0.001px solid #6E7783; /* 調整框線顏色 */
    padding: 8px;
    text-align: left;
}

th {
		background-color: #475F77;
		color: 	white;
		}

		.add-button {
		 background-color: 	#354B5E;
		color: white;
		padding: 10px 20px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		font-size: 16px;
		margin: 20px; /* 調整按鈕的外邊距 */
		}



		body {
		background-color: #DCDDD8; /* 設定整個網頁的背景顏色 */
		margin: 0; /* 移除預設邊距 */
		}
		.edit-button {
		background-color: 	#354B5E;
		color: white;
		padding: 3px 6px; /* 調整按鈕的大小 */
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		font-size: 18.5px;
		margin: 0.01px; /* 調整按鈕的外邊距 */
		}

		.edit-button:hover {
		background-color: #4E5563; /* 在:hover時改變的背景顏色 */
		}

		.delete-button {
		background-color: #4F9D9D;
		color: white;
		padding: 3px 6px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		font-size: 18.5px;
	
    
		}
		.delete-button:hover {
		background-color: #4E5563; /* 在:hover時改變的背景顏色 */
		}
</style>
</head>
<body>
<div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">使用者</button>
            <div class="dropdown-content">
            <a <?php if (!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type'] !== 'manager')) echo 'class="active"'; ?> href="manage_user.php">使用者名單</a>
            <a <?php if (isset($_GET['type']) && $_GET['type'] === 'manager') echo 'class="active"'; ?> href="manage_user.php?type=manager">管理者名單</a>
            <!-- 新增使用者按鈕 -->
            <?php if (!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type'] !== 'manager')) : ?>
            <?php endif; ?>
        </div>
    </div>

        <a href="seatdetail.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
    <button class='add-button' onclick="location.href='manage_usercreate.php'" style='float:left; margin: 20px;'>新增使用者</button>
</div>
<?php
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $sql = "";

    if (isset($_GET['type']) && $_GET['type'] === 'manager') {
        $sql = "SELECT * FROM user WHERE isManager = 1"; // 只顯示管理者
    } else {
        $sql = "SELECT * FROM user WHERE isManager = 0"; // 只顯示一般使用者
    }

    $result = $con->query($sql);
    

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
            <button class='edit-button' onclick=\"redirectToEditPage('" . $row['User_Id'] . "', '" . $row['User_Account'] . "', '" . $row['User_Password'] . "', '" . $row['Number_of_Violation'] . "', '" . $row['Suspension']. "', '" . $row['isManager'] . "')\">編輯</button>
            <button class='delete-button' onclick=\"redirectToDeletePage('" . $row['User_Id'] . "')\">刪除</button>
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
   
</script>
</body>
</html>
