<?php
session_start();

if (isset($_SESSION['account'])) {
    $useraccount = $_SESSION['account'];

    $con = new mysqli("localhost", "root", "eva65348642", "librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 先將 POST 的資料存入變數
        $userId = $_POST['User_Id'];
        $userAccount = $_POST['User_Account'];
        $userPassword = $_POST['User_Password'];
    
        // 使用 UPDATE 語句更新特定使用者資料
        $updateQuery = "UPDATE user SET User_Account='$userAccount', User_Password='$userPassword' WHERE User_Id = '$userId'";
    
        if ($con->query($updateQuery) === TRUE) { ?>
            <script language="javascript">
                alert('會員資料修改完成！**請重新登入**');
                location.href="../logout.php";
            </script>
        <?php } else {
            echo "Error updating record: " . $con->error;
        }
    }
    

    $user = $con->query("SELECT * FROM user WHERE User_Account='$useraccount'");

    if ($user && $user->num_rows > 0) {
        $row = $user->fetch_assoc();
    }
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
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
        <a href='../userstatus.php'>會員</a>
        <a href='seat/seat.php'>座位</a>
        <a href='../reservation/reservation.php'>預約紀錄</a>
        <a href='../reservation/reservation.php'>新增預約</a>
        <!-- 登入、登出 -->
        <a href='logout.php' style='float:right;'>登出</a>
        <h4 style='float:right;'><font color='white'><?php echo isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!'; ?></font></h4>
    </div>

    <!-- 表單 -->
    <h1>Edit User Data</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- 使用者 ID 作為隱藏輸入 -->
        <input type="hidden" name="User_Id" value="<?php echo $row['User_Id']; ?>">
        
        <label for="User_Account">帳號：</label>
        <input type="text" id="User_Account" name="User_Account" value="<?php echo $row['User_Account']; ?>"><br><br>
        
        <label for="User_Password">密碼：</label>
        <input type="password" id="User_Password" name="User_Password" value="<?php echo $row['User_Password']; ?>"><br><br>
        <!--
        <label for="Arrival_Time">抵達時間：</label>
        <input type="datetime-local" id="Arrival_Time" name="Arrival_Time" value="<?php echo date('Y-m-d\TH:i', strtotime($row['Arrival_Time'])); ?>"><br><br>

        <label for="Departure_Time">離開時間：</label>
        <input type="datetime-local" id="Departure_Time" name="Departure_Time" value="<?php echo date('Y-m-d\TH:i', strtotime($row['Departure_Time'])); ?>"><br><br>

        <label for="Suspension">停權：</label>
        <input type="number" id="Suspension" name="Suspension" value="<?php echo $row['Suspension']; ?>"><br><br>

        <label for="Number_of_Violation">違規次數：</label>
        <input type="number" id="Number_of_Violation" name="Number_of_Violation" value="<?php echo $row['Number_of_Violation']; ?>"><br><br>
        -->  
        <input type="submit" value="儲存修改">
    </form>
</body>
</html>
