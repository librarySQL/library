<?php
session_start();

$con = new mysqli("localhost", "root", "eva65348642", "librarydb");


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$query = "SELECT MAX(User_Id) AS maxUserId FROM user";
$result = $con->query($query);

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
		body {
		background-color: #DCDDD8; /* 設定整個網頁的背景顏色 */
		margin: 0; /* 移除預設邊距 */
		}
		.btn{
			background-color: 	#354B5E;
		color: white;
		padding: 3px 6px; /* 調整按鈕的大小 */
	
		font-size: 14.5px;
		}
        
    </style>
	
	
	 <script>
        function validateForm() {
            var isManagerValue = document.getElementById("isManager").value;

            // Check if isManagerValue is not 0 or 1
            if (isManagerValue !== "0" && isManagerValue !== "1") {
                alert("是否為管理者？請輸入 1 （true）或 0（false）");
                return false; // Prevent form submission
            }

            // If validation passes, allow form submission
            return true;
        }
    </script>
	
</head>
<body>

<?php

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nextUserId = $row['maxUserId'] + 1;
} else {
    // Default to 1 if no existing users
    $nextUserId = 1;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = mysqli_real_escape_string($con, $_POST["userId"]);
    $userAccount = mysqli_real_escape_string($con, $_POST["userAccount"]);
    $userPassword = mysqli_real_escape_string($con, $_POST["userPassword"]);
    $numberOfViolation = isset($_POST["numberOfViolation"]) ? mysqli_real_escape_string($con, $_POST["numberOfViolation"]) : null;
    $suspension = isset($_POST["suspension"]) ? mysqli_real_escape_string($con, $_POST["suspension"]) : null;
    $isManager = mysqli_real_escape_string($con, $_POST["isManager"]);

    // Set to NULL if empty
    $numberOfViolation = $numberOfViolation !== '' ? $numberOfViolation : null;
    $suspension = $suspension !== '' ? $suspension : null;

    $insertSql = "INSERT INTO user (User_Id, User_Account, User_Password, Number_of_Violation, Suspension, isManager) 
                  VALUES ('$userId', '$userAccount', '$userPassword', ";
    $insertSql .= $numberOfViolation !== null ? "'$numberOfViolation'" : "NULL";
    $insertSql .= ", ";
    $insertSql .= $suspension !== null ? "'$suspension'" : "NULL";
    $insertSql .= ", '$isManager')";

    if ($con->query($insertSql) === TRUE) {
        // Redirect to manage_user.php
        header("Location: manage_user.php");
        exit;
    } else {
        echo "错误：" . $insertSql . "<br>" . $con->error;
    }
}



?>

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
	</div>
    <h1 style="text-align: center; margin-top: 20px;">Create A New User</h1>
<div style="text-align: center; margin-top: 20px;">
    <form method="post" action="" onsubmit="return validateForm();">
        <label for="userId" style="text-align: left; display: inline-block; width: 100px;">UserId：</label>
		<input type="text" id="userId" name="userId" value="<?php echo $nextUserId; ?>" readonly style="margin-bottom: 10px;"><br>

        <label for="userAccount" style="text-align: left; display: inline-block; width: 100px;">帳號：</label>
        <input type="text" id="userAccount" name="userAccount" required style="margin-bottom: 10px;"><br>

        <label for="userPassword" style="text-align: left; display: inline-block; width: 100px;">密碼：</label>
        <input type="password" id="userPassword" name="userPassword" required style="margin-bottom: 10px;"><br>

        <label for="numberOfviolation" style="text-align: left; display: inline-block; width: 100px;">違規次數：</label>
		<input type="text" id="numberOfviolation" name="numberOfviolation" style="margin-bottom: 10px;"><br>

		<label for="suspension" style="text-align: left; display: inline-block; width: 100px;">停權：</label>
		<input type="text" id="suspension" name="suspension" style="margin-bottom: 10px;"><br>

		<label for="isManager" style="text-align: left; display: inline-block; width: 100px;">身分：</label>
        <select id="isManager" name="isManager" required style="width:12%;height: 20px;margin-bottom: 20px;">
            <option value="0">使用者</option>
            <option value="1">管理者</option>
        </select><br>

        <!-- Add other input fields as needed -->

        <input class="btn" type="submit" value="儲存">
    </form>
</div>

<div style="text-align: center; margin-top: 30px;">
    <a href="manage_user.php" style="text-align: left;">返回</a>
</div>




<?php
$con->close();
?>

</body>
</html>
