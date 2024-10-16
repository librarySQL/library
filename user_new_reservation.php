<?php
session_start();
$con = new mysqli("localhost", "root", "yourpassword", "圖書館座位預約系統");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// 检查用户是否已登录，如果未登录则重定向到登录页面
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// 显示用户帐户信息
if (isset($_SESSION['account']) ) {
    $userId = $_SESSION['account'];
    //$suspention=$_SESSION['suspention'];
    // 这里你可以使用 $userId 查询数据库或其他存储来获取用户信息
    // 在这个示例中，我们仅显示 "account 您好！" 的消息
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
    //$Msg="您以違規".$_SESSION['suspention']."次!";
} else {
    // 如果未找到用户ID，可能需要再次重定向到登录页面或者显示错误信息
    header("Location: login.php");
    exit;
}

if (isset($_POST['search']) && !empty($_POST['starttime']) && !empty($_POST['endtime']) && !empty($_POST['seatfloor']) && !empty($_POST['socket'])) {

    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $seatfloor = $_POST['seatfloor'];
    $socket = $_POST['socket'];
     
    // 开始时间验证 - 10:00以后可以预订
     $libraryOpeningTime = strtotime('today 10:00');
     if (strtotime($starttime) < $libraryOpeningTime) {
         $mes = "圖書館從早上10:00開始接受預約座位！";
     }
 
     // 结束时间验证 - 不晚于当天的22:00
     $libraryClosingTime = strtotime('today 22:00');
     if (strtotime($endtime) > $libraryClosingTime) {
         $mes = "圖書館晚上10:00就不再接受預約座位了！";
     }

    // 查询可用座位
    $available_seats_query = "SELECT Seat_Floor, Seat_Name, Socket FROM seat s
        WHERE s.Seat_Id NOT IN (
            SELECT r.Seat_Id FROM reservation r
            WHERE (r.Start_Time < '$endtime' AND r.End_Time > '$starttime')
        ) 
        AND s.Seat_Floor = '$seatfloor' 
        AND s.Socket = '$socket'";

    $available_seats_result = $con->query($available_seats_query);

    if ($available_seats_result->num_rows > 0) {
        $available_seat_names = array();
        while ($row = mysqli_fetch_array($available_seats_result)) {
            $available_seat_names[] = $row['Seat_Name'];
        }

        $_SESSION['available_seats'] = $available_seat_names;
        header("Location: select_seat.php");
        exit;
    } else {
        $mes = "暫無可預約座位";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增預約</title>
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
		padding: 3px 6px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		font-size: 16px;
		}

		.edit-button:hover {
		background-color: #4E5563; /* 在:hover時改變的背景顏色 */
		}

		.delete-button {
		background-color: #f3dae0;
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
        .search {
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
        
        .flatpickr-calendar {
        transform: scale(0.85); /* 縮小日曆大小為原來的 80% */
        transform-origin: top left; /* 設置縮放原點，使其從左上角開始縮放 */
        }
        

</style>
   
     <!-- 其他標頭內容 -->
     <script>
function redirectToSelectSeat() {
    var starttime = document.getElementsByName('starttime')[0].value;
    var endtime = document.getElementsByName('endtime')[0].value;
    var seatfloor = document.getElementsByName('seatfloor')[0].value;
    var socket = document.getElementsByName('socket')[0].value;

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "select_seat.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "starttime");
    hiddenField.setAttribute("value", starttime);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "endtime");
    hiddenField.setAttribute("value", endtime);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "seatfloor");
    hiddenField.setAttribute("value", seatfloor);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "socket");
    hiddenField.setAttribute("value", socket);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}


</script>

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
<br><br>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <form id="reservationForm" class="form-signin" role="form" onsubmit="redirectToSelectSeat(); return false;">
    <!-- 開始時間 -->


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div align="center">
    
    <label for="starttime"><b>開始時間:</b></label><br>
    <input type="text" id="starttime" name="starttime" placeholder="選擇開始時間" readonly style="width: 200px; height: 40px; font-size: 16px;">
    <br><br>
    <label for="endtime"><b>結束時間:</b></label><br>
    <input type="text" id="endtime" name="endtime" placeholder="選擇結束時間" readonly style="width: 200px; height: 40px; font-size: 16px;">

</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
    var startTimeInput = flatpickr("#starttime", {
        enableTime: true,
        minTime: "8:00",
        maxTime: "22:00",
        dateFormat: "Y-m-d H:i", // 添加日期時間格式
        onClose: function(selectedDates, dateStr, instance) {
            var selectedStartTime = new Date(dateStr);
            var selectedEndTime = new Date(document.getElementById('endtime').value);
            if (selectedStartTime > selectedEndTime) {
                alert('開始時間不能晚於結束時間。');
                location.reload(); // 刷新頁面
            }
        }
    });

    var endTimeInput = flatpickr("#endtime", {
        enableTime: true,
        minTime: "8:00",
        maxTime: "22:00",
        dateFormat: "Y-m-d H:i", // 添加日期時間格式
        onClose: function(selectedDates, dateStr, instance) {
            var selectedEndTime = new Date(dateStr);
            var selectedStartTime = new Date(document.getElementById('starttime').value);
            if (selectedStartTime > selectedEndTime) {
                alert('開始時間不能晚於結束時間。');
                location.reload(); // 刷新頁面
            }
        }
    });
});
</script>

    <br>
            <div align="center">
            <b><label for="endtime">座位樓層: </label></b>
            <br>    
            <select class="form-control" required="required" name="seatfloor" style="width:30%;height: 40px;">
        <option value="1">1樓</option>
        <option value="2">2樓</option>
        <option value="3">3樓</option>
    </select></div><br>
            <div align="center">
            <b><label for="endtime">座位插座: </label></b>    
            <br>
            <select class="form-control" required="required" name="socket" style="width:30%;height: 40px;">
        <option value="有">有</option>
        <option value="無">無</option>
    </select>
        </div><br>
            <!-- 前面的代码保持不变 -->
            <div align="center">
        <input class="search"type="submit" value="查詢可預約座位" style="width:30%;height: 40px;">
    </div>
        </Form>
    </div>
</body>
</html>
