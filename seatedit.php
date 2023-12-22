<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 通过 POST 提交
    $seatId = $_POST['Seat_Id'];
    $seatName = $_POST['Seat_Name'];
    $seatFloor = $_POST['Seat_Floor'];
    $socket = $_POST['Socket'];
  
  } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
    // 通过 GET 方式访问
    if (isset($_GET['seatId']) && isset($_GET['seatName']) && isset($_GET['seatFloor']) && isset($_GET['socket'])) {
      $seatId = $_GET['seatId'];
      $seatname = $_GET['seatName'];
      $seatfloor = $_GET['seatFloor'];
      $socket = $_GET['socket'];
  
    } else {
      // 设置默认值
      $seatId = '';
      $seatname = '';
      $seatfloor = '';
      $socket = '';
    }
  
  }

if (isset($_SESSION['account'])) {
    // 检查用户是否登录
    $useraccount = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    $con = new mysqli("localhost", "root", "ccl5266ccl", "圖書館座位預約系統");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // 根据 Seat ID 从数据库中检索相关信息
    $query="SELECT Seat_Id,Seat_Name,Seat_Floor,Socket
    FROM seat
    WHERE Seat_Id = '$seatId'";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // 获取默认值
        $seatId = $row['Seat_Id'];
        $seatname = $row['Seat_Name'];
        $seatfloor = $row['Seat_Floor'];
        $socket = $row['Socket'];
    } else {
        
        echo "No seat found for the given ID.";
        exit; // 如果找不到，终止程序
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 如果是 POST 请求，检查是否存在 seatId，并从 POST 数据中获取其值
        if (isset($_POST['Seat_Id'])) {
            $seatId = $_POST['Seat_Id'];
            //echo $seatId;

            // 只更新座位信息
            $seatname = $_POST['Seat_Name'];
            $seatfloor = $_POST['Seat_Floor'];
            $socket = $_POST['Socket'];
            $updateQuery = "UPDATE seat
                SET Seat_Name='$seatname', Seat_Floor='$seatfloor', Socket='$socket'
                WHERE Seat_Id = '$seatId'";

            if (!empty($updateQuery)) {
                $result = $con->query($updateQuery);

                if ($result === TRUE && $con->affected_rows > 0) { 
                    ?>
       
                    <script language="javascript">
                        alert('預約資料修改完成！');
                        location.href="allseat.php";
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
            echo "Seat ID not found in POST data.";
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
    <h1>Edit Seat Data</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- 座位编号 -->
        <label for="Seat_Id">座位Id:</label>
        <input type="text" id="Seat_Id" name="Seat_Id" value="<?php echo $seatId; ?>" readonly><br><br>
        <!-- 座位名称 -->
        <label for="Seat_Name">座位名稱:</label>
        <input type="text" id="Seat_Name" name="Seat_Name" value="<?php echo $seatname; ?>" required><br><br>

        <!-- 座位楼层 -->
        <label for="Seat_Floor">座位樓層:</label>
        <input type="text" id="Seat_Floor" name="Seat_Floor" value="<?php echo $seatfloor; ?>" required><br><br>

        <!-- 插座 -->
        <label for="Socket">插座:</label>
        <input type="text" id="Socket" name="Socket" value="<?php echo $socket; ?>" required><br><br>

        <!-- 提交按钮 -->
        <input type="submit" value="儲存修改">
    </form>
</body>
</html>
