//用seatedit去改的 但還沒弄出來

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 通过 POST 提交
    $userId = $_POST['userId'];
    $account = $_POST['account'];
    $password = $_POST['password'];
    $numberOfViolation = $_POST['numberOfViolation'];
	$suspension = $_POST['suspension'];
	$isManager = $_POST['isManager'];
  
  } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
    // 通过 GET 方式访问
    if (isset($_GET['userId']) && isset($_GET['account']) && isset($_GET['password']) && isset($_GET['numberOfViolation'])&& isset($_GET['suspension'])&& isset($_GET['isManager'])) {
     $userId = $_POST['userId'];
    $account = $_POST['account'];
    $password = $_POST['password'];
    $numberOfViolation = $_POST['numberOfViolation'];
	$suspension = $_POST['suspension'];
	$isManager = $_POST['isManager'];
  
    } else {
      // 设置默认值
      $userId = '';
      $account = '';
      $password = '';
      $numberOfViolation = '';
	  $suspension = '';
	  $isManager = '';
    }
  
  }

if (isset($_SESSION['account'])) {
    // 检查用户是否登录
    $useraccount = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    $con = new mysqli("localhost", "root", "jenny104408!", "libdb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // 根据 Seat ID 从数据库中检索相关信息
    $query="SELECT User_Id,User_Account,User_Password,Number_of_Violation,Suspension,isManager
    FROM user
    WHERE User_Id = '$userId'";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // 获取默认值
    $userId = $_POST['userId'];
    $account = $_POST['account'];
    $password = $_POST['password'];
    $numberOfViolation = $_POST['numberOfViolation'];
	$suspension = $_POST['suspension'];
	$isManager = $_POST['isManager'];
    } else {
        
        echo "No seat found for the given ID.";
        exit; // 如果找不到，终止程序
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 如果是 POST 请求，检查是否存在 seatId，并从 POST 数据中获取其值
        if (isset($_POST['userId'])) {
            $userId = $_POST['userId'];
            //echo $seatId;

            // 只更新座位信息
            $account = $_POST['account'];
			$password = $_POST['password'];
			$numberOfViolation = $_POST['numberOfViolation'];
			$suspension = $_POST['suspension'];
			$isManager = $_POST['isManager'];
            $updateQuery = "UPDATE user
                SET User_Account='$account', User_Password='$password', Number_of_Violation='$numberOfViolation', Suspension='$suspension',isManager='$isManager'
                WHERE User_Id = '$userId'";

            if (!empty($updateQuery)) {
                $result = $con->query($updateQuery);

                if ($result === TRUE && $con->affected_rows > 0) { 
                    ?>
       
                    <script language="javascript">
                        alert('預約資料修改完成！');
                        location.href="manage_user.php";
                    </script>
                    
                    <?php 
                    echo"成功!";
                } else {
                    echo "Error updating record: " . $con->error;
                }
            } else {
                echo "Update query is empty or invalid.";
            }
        } else {
            echo "User ID not found in POST data.";
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
    <!-- 表單 -->
    <h1>Edit User Data</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- 座位编号 -->
        <label for="User_Id">UserId:</label>
        <input type="text" id="User_Id" name="User_Id" value="<?php echo $userId; ?>" readonly><br><br>
        <!-- 座位名称 -->
        <label for="User_Account">帳號:</label>
        <input type="text" id="User_Account" name="User_Account" value="<?php echo $account; ?>" required><br><br>

        <!-- 座位楼层 -->
        <label for="User_Password">密碼:</label>
        <input type="text" id="User_Password" name="User_Password" value="<?php echo $password; ?>" required><br><br>

        <!-- 插座 -->
        <label for="Number_of_Violation">違規次數:</label>
        <input type="text" id="Number_of_Violation" name="Number_of_Violation" value="<?php echo $numberOfViolation; ?>" ><br><br>

		<label for="Suspension">停權：:</label>
        <input type="text" id="Suspension" name="Suspension" value="<?php echo $suspension; ?>" ><br><br>
		
		<label for="isManager">管理者:</label>
        <input type="text" id="isManager" name="isManager" value="<?php echo $isManagern; ?>" required><br><br>
        <!-- 提交按钮 -->
        <input type="submit" value="儲存修改">
    </form>
</body>
</html>
