<?php
session_start();


if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    
} else {
   
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
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
    border=1
    border-collapse: collapse;
    margin-top: 20px; /* 調整與按鈕的間距 */
}

th, td {
    border: 0.001px solid #6E7783; /* 調整框線顏色 */
    padding: 8px;
    text-align: left;
}

th {
		background-color: #9db0c9;
		color: 	black;
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
		background-color: #ced8e4 ; /* 設定整個網頁的背景顏色 */
		margin: 0; /* 移除預設邊距 */
		}
		.edit-button {
		background-color: #feeba8;
		color: #3e3e3e; 
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
		background-color: #f1ddac;
		color: #3e3e3e; 
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
    <a href="userstatus.php">會員</a>
    <a href="seat.php">座位一覽</a>
    <a href="user_reservation.php">預約紀錄</a>
    <a href="user_new_reservation.php">預約座位</a>
    <a href="search_seat.php">查詢座位</a>
    
    <!-- 登入、登出 -->
    <a href="logout.php" style="float:right;">登出</a>
    <h4 style="float:right;"><font color="white"><?php echo $accountMessage; ?></font></h4>
   
    <!-- 可以加入其他需要的連結 -->
</div>
<?php
//session_start();
$con = new mysqli("localhost", "root", "yourpassword", "圖書館座位預約系統");  

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$useraccount = $_SESSION['account'];

$query = "SELECT r.Reservation_Id, u.User_Account, s.Seat_Name, s.Seat_Floor, s.Socket, r.Start_Time, r.End_Time
    FROM reservation r
    JOIN user u ON r.User_Id = u.User_Id
    JOIN seat s ON r.Seat_Id = s.Seat_Id
    WHERE u.User_Account = '$useraccount'";


$test = $con->query($query);
echo "<table>
    <tr>
        <th>使用者</th>
        <th>座位編號</th>
        <th>開始時間</th>
        <th>結束時間</th> 
        <th>座位樓層</th>
        <th>插座</th>
        <th></th>
    </tr>";

    while ($row = mysqli_fetch_array($test)) {
        echo "<tr>";
        echo "<td>" . $row['User_Account'] . "</td>";
        echo "<td>" . $row['Seat_Name'] . "</td>";
        echo "<td>" . $row['Start_Time'] . "</td>";
        echo "<td>" . $row['End_Time'] . "</td>"; 
        echo "<td>" . $row['Seat_Floor'] . "</td>";
        echo "<td>" . $row['Socket'] . "</td>";
        echo "<td>
        <button class='edit-button' onclick=\"redirectToEditPage('" . $row['Reservation_Id'] . "', '" . $row['User_Account'] . "', '" . $row['Seat_Name'] . "', '" . $row['Start_Time'] . "', '" . $row['End_Time'] . "', '" . $row['Seat_Floor'] . "', '" . $row['Socket'] . "')\">編輯</button>
        <button class='delete-button' onclick=\"redirectToDeletePage('" . $row['Reservation_Id'] . "')\">取消預約</button>
              </td>";
        echo "</tr>";
    }
echo "</table>";

echo "<script>
        // 獲取按钮元素 
        function redirectToEditPage(reservationId,account, seatName, startTime, endTime, seatFloor, socket) {
                    
            var editPageUrl = 'user_reservation_edit.php' + '?reservationId=' + encodeURIComponent(reservationId) + '&account=' + encodeURIComponent(account) + '&seatName=' + encodeURIComponent(seatName) + '&startTime=' + encodeURIComponent(startTime) + '&endTime=' + encodeURIComponent(endTime) + '&seatFloor=' + encodeURIComponent(seatFloor) + '&socket=' + encodeURIComponent(socket);
            
            
            window.location.href = editPageUrl;
        }


                function redirectToDeletePage(reservationId) {
                var deleteConfirmation = confirm('是否要取消此訂單？');

                if (deleteConfirmation) {
                var deletePageUrl = 'user_reservation_delete.php' + '?reservationId=' + encodeURIComponent(reservationId);
            
                // 使用 JavaScript 跳轉到取消預約頁面
                window.location.href = deletePageUrl;
                } else {
                // 使用者選擇取消，不執行任何操作
        }
    }
</script>";


$con->close();
?>
</body>
</html>
