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
session_start();

$con = new mysqli("localhost", "root", "jenny104408!", "libdb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$query = "SELECT MAX(User_Id) AS maxUserId FROM user";
$result = $con->query($query);


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
        header("Location: ../manager/manage_user.php");
        exit;
    } else {
        echo "错误：" . $insertSql . "<br>" . $con->error;
    }
}


echo 
"<div class='navbar'>
    <a href='../manager/manage_user.php'>使用者</a>
	<a href='../manager/manage_usercreate.php'>新增使用者</a> 
   
    <!-- 登入、登出 -->
    <a href='../logout/logout.php' style='float:right;'>登出</a>
    
    
    <!-- 可以加入其他需要的連結 -->
</div>";

?>

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

		<label for="isManager" style="text-align: left; display: inline-block; width: 100px;">管理者：</label>
        <input type="text" id="isManager" name="isManager" required style="margin-bottom: 20px;"><br>

        <!-- Add other input fields as needed -->

        <input type="submit" value="儲存">
    </form>
</div>

<div style="text-align: center; margin-top: 30px;">
    <a href="../manager/manage_user.php" style="text-align: left;">返回</a>
</div>




<?php
$con->close();
?>

</body>
</html>
