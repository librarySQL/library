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
    $con = new mysqli("localhost", "root", "eva65348642", "librarydb");

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
                        alert('座位狀況修改完成！');
                        location.href="seatdetail.php";
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

        <a href="seatdetail.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
    <!-- 表單 -->
    <div style="text-align: center; margin-top: 20px;">
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
<select id="Seat_Floor" name="Seat_Floor" style="width:10%;height: 30px;"required>
    <option value="1" <?php if ($seatfloor == '1') echo 'selected'; ?>>1樓</option>
    <option value="2" <?php if ($seatfloor == '2') echo 'selected'; ?>>2樓</option>
    <option value="3" <?php if ($seatfloor == '3') echo 'selected'; ?>>3樓</option>
</select><br><br>


        <!-- 插座 -->
<label for="Socket">插座:</label>
<select id="Socket" name="Socket" style="width:10%;height: 30px;" required>
    <option value="有" <?php if ($socket == '有') echo 'selected'; ?>>有</option>
    <option value="無" <?php if ($socket == '無') echo 'selected'; ?>>無</option>
</select><br><br>


        <!-- 提交按钮 -->
        <input class="inputbtn" type="submit" value="儲存修改">
    </form>
    </div>
</body>
</html>
