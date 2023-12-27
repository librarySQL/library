<?php
session_start();

// 连接数据库
$con = new mysqli("localhost", "root", "jenny104408!", "libdb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 通过 POST 提交
    $userId = $_POST['User_Id'];
    $account = $_POST['User_Account'];
    $password = $_POST['User_Password'];
    $numberOfViolation = $_POST['Number_of_Violation'];
    $suspension = $_POST['Suspension'];
    $isManager = $_POST['isManager'];

    // 处理空值或无效值的情况
    $numberOfViolation = empty($numberOfViolation) ? 0 : intval($numberOfViolation);
    $suspension = empty($suspension) ? 0 : intval($suspension);
    $isManager = empty($isManager) ? 0 : intval($isManager);

    // 更新数据库中的用户信息
    $updateQuery = "UPDATE user
                    SET User_Account='$account', User_Password='$password', Number_of_Violation='$numberOfViolation', Suspension='$suspension', isManager='$isManager'
                    WHERE User_Id = '$userId'";


    if (!empty($updateQuery)) {
        $result = $con->query($updateQuery);

        if ($result === TRUE && $con->affected_rows > 0) { 
            ?>
            <script language="javascript">
                alert('使用者資料修改完成！');
                location.href="manage_user.php";
            </script>
            <?php 
            echo "成功!";
        } else {
            echo "Error updating record: " . $con->error;
        }
    } else {
        echo "Update query is empty or invalid.";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // 通过 GET 方式访问
    if (isset($_GET['userId'])) {
        $userId = $_GET['userId'];

        // 根据 User ID 从数据库中检索相关信息
        $query = "SELECT User_Id, User_Account, User_Password, Number_of_Violation, Suspension, isManager
                  FROM user
                  WHERE User_Id = '$userId'";

        $result = $con->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // 获取数据库中的用户信息
            $userId = $row['User_Id'];
            $account = $row['User_Account'];
            $password = $row['User_Password'];
            $numberOfViolation = $row['Number_of_Violation'];
            $suspension = $row['Suspension'];
            $isManager = $row['isManager'];
        } else {
            echo "No user found with this ID.";
        }
    } else {
        // 如果未提供 userId，设置默认值或进行其他处理
        $userId = '';
        $account = '';
        $password = '';
        $numberOfViolation = '';
        $suspension = '';
        $isManager = '';
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
	<style>
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
		body {
		background-color: #DCDDD8; /* 設定整個網頁的背景顏色 */
		margin: 0; /* 移除預設邊距 */
		}
		.inputbtn {
		background-color: 	#354B5E;
		color: white;
		padding: 3px 6px; /* 調整按鈕的大小 */
	
		font-size: 14.5px;
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

        <a href="manage_seat.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
	</div>
    <!-- 表單 -->
    <h1 style="text-align: center;">Edit User Data</h1>
    <div style="text-align: center; margin-top: 20px;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- 使用者编号 -->
        <label for="User_Id">UserId:</label>
        <input type="text" id="User_Id" name="User_Id" value="<?php echo $userId; ?>" readonly><br><br>
        <!-- 使用者帳號 -->
        <label for="User_Account">帳號:</label>
        <input type="text" id="User_Account" name="User_Account" value="<?php echo $account; ?>" required><br><br>

        <!-- 使用者密碼 -->
        <label for="User_Password">密碼:</label>
        <input type="text" id="User_Password" name="User_Password" value="<?php echo $password; ?>" required><br><br>

        <!-- 違規次數 -->
        <label for="Number_of_Violation">違規次數:</label>
        <input type="text" id="Number_of_Violation" name="Number_of_Violation" value="<?php echo $numberOfViolation; ?>" ><br><br>
        <!-- 停權次數 -->
        <label for="Suspension">停權：:</label>
        <input type="text" id="Suspension" name="Suspension" value="<?php echo $suspension; ?>" ><br><br>
        <!-- 是否為管理者 -->
        <label for="isManager">管理者:</label>
        <select id="isManager" name="isManager" required>
        <option value="1" <?php if ($isManager == 1) echo 'selected'; ?>>是</option>
        <option value="0" <?php if ($isManager == 0) echo 'selected'; ?>>否</option>
        </select><br><br>
        <!-- 提交按钮 -->
        <input class="inputbtn" type="submit" value="儲存修改">
    </form>
</div>
</body>
</html>
