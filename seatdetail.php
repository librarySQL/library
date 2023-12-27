<?php
session_start();

// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
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
    <title>座位</title>
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
        
		<a href="manage_user.php">使用者</a>
        <a href="manage_seat.php">座位狀況</a>
        <!-- 登入、登出 -->
        <a href="logout.php" style="float:right;">登出</a>
		<h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
    </div>
	<button class='add-button' onclick="location.href='manage_seatcreate.php'" style='float:left; margin: 20px;'>新增座位</button>
<?php

$con = new mysqli("localhost", "root", "jenny104408!", "libdb");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

 $seat = $con->query("SELECT Seat_ID, Seat_Name, Seat_Floor, Socket FROM seat");


echo "<table>
    <tr>
        <th>座位編號</th>
        <th>座位樓層</th>
        <th>插座</th>
        <th>更新</th>
    </tr>";

    while ($row = mysqli_fetch_array($seat)) {
        echo "<tr>";
        echo "<td>" . $row['Seat_Name'] . "</td>";
        echo "<td>" . $row['Seat_Floor'] . "</td>";
        echo "<td>" . $row['Socket'] . "</td>";
        echo "<td> 
        <button class='edit-button' onclick=\"redirectToEditPage('". $row['Seat_ID']."', '".$row['Seat_Name']."','".$row['Seat_Floor']."', '".$row['Socket']."')\">編輯</button>

        <button class='delete-button' onclick=\"deleteSeat('{$row['Seat_ID']}')\">刪除</button> </td>";
        echo "</tr>";
    }
    

echo "</table>";
echo "<script>
function redirectToEditPage(seatId, seatName, seatFloor, socket) {
    var editPageUrl = 'manage_seatedit.php' + '?seatId=' + encodeURIComponent(seatId) + '&seatName=' + encodeURIComponent(seatName) + '&seatFloor=' + encodeURIComponent(seatFloor) + '&socket=' + encodeURIComponent(socket);
    window.location.href = editPageUrl;
}

function deleteSeat(seatID) {
    var deleteConfirmation = confirm('是否要刪除此座位？');

    if (deleteConfirmation) {
        var deletePageUrl = 'manage_seatdelete.php?id=' + seatID;
        window.location.href = deletePageUrl;
    } else {
        // 用户取消删除操作，不执行任何操作
    }
}
</script>";
$con->close();
?>

</body>
</html>
