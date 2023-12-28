<?php
session_start();
$con = new mysqli("localhost", "root", "eva65348642", "librarydb");

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

    // 獲取開始時間和結束時間的 input 元素
    var startTimeInput = document.getElementById('starttime');
    var endTimeInput = document.getElementById('endtime');

    // 當結束時間改變時執行驗證
    endTimeInput.addEventListener('change', function() {
        // 獲取開始時間和結束時間的值
        var startTimeValue = new Date(startTimeInput.value);
        var endTimeValue = new Date(endTimeInput.value);

        // 驗證結束時間不早於開始時間
        if (endTimeValue < startTimeValue) {
            alert('結束時間不能早於開始時間');
            endTimeInput.value = ''; // 清空結束時間欄位
        }
    });

    // 當開始時間改變時執行驗證
    startTimeInput.addEventListener('change', function() {
        // 獲取開始時間和結束時間的值
        var startTimeValue = new Date(startTimeInput.value);
        var endTimeValue = new Date(endTimeInput.value);

        // 驗證開始時間不晚於結束時間
        if (startTimeValue > endTimeValue) {
            alert('開始時間不能晚於結束時間');
            startTimeInput.value = ''; // 清空開始時間欄位
        }
    });
</script>

</head>
<body> 
<div class="navbar">
    <a href="userstatus.php">會員</a>
    <a href="seat.php">座位一覽</a>
    <a href="reservation.php">預約紀錄</a>
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
    <div align="center">
    <b><label for="endtime">開始時間： </label></b>
    <br>
    <input type="datetime-local" class="form-control" required="required" name="starttime" id="starttime" placeholder="starttime" style="width:30%;height: 40px;">
    </div>
    <br>
    <!-- 結束時間 -->
    <div align="center">
    <b><label for="endtime">結束時間:</label></b>
        <br>
        <input type="datetime-local" class="form-control" required="required" name="endtime" id="endtime" placeholder="endtime" style="width:30%;height: 40px;">
    </div>
    <script>
            // 獲取開始時間和結束時間的 input 元素
            var startTimeInput = document.getElementById('starttime');
            var endTimeInput = document.getElementById('endtime');

            // 當結束時間改變時執行驗證
            endTimeInput.addEventListener('change', function() {
                // 獲取開始時間和結束時間的值
                var startTimeValue = new Date(startTimeInput.value);
                var endTimeValue = new Date(endTimeInput.value);

                // 驗證結束時間不早於開始時間
                if (endTimeValue < startTimeValue) {
                    alert('結束時間不能早於開始時間');
                    endTimeInput.value = ''; // 清空結束時間欄位
                }
            });

            // 當開始時間改變時執行驗證
            startTimeInput.addEventListener('change', function() {
                // 獲取開始時間和結束時間的值
                var startTimeValue = new Date(startTimeInput.value);
                var endTimeValue = new Date(endTimeInput.value);

                // 驗證開始時間不晚於結束時間
                if (startTimeValue > endTimeValue) {
                    alert('開始時間不能晚於結束時間');
                    startTimeInput.value = ''; // 清空開始時間欄位
                }
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
            <input type="text" class="form-control" require="require" name="socket"  placeholder="socket" style="width:30%;height: 40px;"></div><br>
            <!-- 前面的代码保持不变 -->
            <div align="center">
        <input class="search"type="submit" value="查詢可預約座位" style="width:30%;height: 40px;">
    </div>
        </Form>
    </div>
</body>
</html>
