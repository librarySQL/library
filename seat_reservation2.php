<?php
session_start();
$conn = new mysqli("localhost", "root", "eva65348642", "librarydb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account'])) {
    $userId = $_SESSION['account'];
    $accountMessage = isset($_SESSION['account']) ? $_SESSION['account'] . " 您好！" : 'Hello!';
} else {
    header("Location: login.php");
    exit;
}

if (isset($_POST['selected_seat'])) {
    $selectedSeat = $_POST['selected_seat'];

    // 查询选定座位的座位樓層和插座
    $seatInfoQuery = "SELECT Seat_Floor, Socket FROM seat WHERE Seat_Name = '$selectedSeat'";
    $seatInfoResult = $conn->query($seatInfoQuery);

    if ($seatInfoResult->num_rows > 0) {
        $row = $seatInfoResult->fetch_assoc();
        $seatFloor = $row['Seat_Floor'];
        $socket = $row['Socket'];
    } else {
        // 如果未找到座位信息，可以提供默认值或错误消息
        $seatFloor = '';
        $socket = '';
    }
} else {
    // 如果未接收到选定的座位，可以提供默认值或错误消息
    $seatFloor = '';
    $socket = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- 在這裡引入 Flatpickr 的 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>可預約座位</title>
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
        transform: scale(0.80); /* 縮小日曆大小為原來的 80% */
        transform-origin: top left; /* 設置縮放原點，使其從左上角開始縮放 */
        }
        

</style>
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
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
        <h2>預約座位</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">

            <input type="hidden" id="User_Account" name="User_Account" value="<?php echo $userid; ?>" ><br><br>

            <label for="seatname">座位:</label>
            <input type="text" id="seatname" name="seatname" value="<?php echo htmlspecialchars($_POST['selected_seat'] ?? ''); ?>" readonly><br><br>

            <script>



<!-- 選擇開始時間與結束時間 -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<label for="starttime">開始時間:</label>
    <input type="text" id="starttime" name="starttime" placeholder="選擇開始時間" readonly>
    <br><br>
    <label for="endtime">結束時間:</label>
    <input type="text" id="endtime" name="endtime" placeholder="選擇結束時間" readonly>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var startTimeInput = flatpickr("#starttime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            onClose: function (selectedDates, dateStr, instance) {
                var selectedStartTime = new Date(dateStr);
                var libraryOpeningTime = new Date(selectedStartTime);
                libraryOpeningTime.setHours(8, 0, 0, 0); // 设置图书馆开放时间为早上8点

                if (selectedStartTime < libraryOpeningTime) {
                    alert('圖書館早上8點才開門！');
                    startTimeInput._flatpickr.clear(); // 清空开始时间栏位
                }
            }
        });

        var endTimeInput = flatpickr("#endtime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            onClose: function (selectedDates, dateStr, instance) {
                var selectedEndTime = new Date(dateStr);
                var libraryClosingTime = new Date(selectedEndTime);
                libraryClosingTime.setHours(22, 0, 0, 0); // 设置图书馆关闭时间为晚上10点

                if (selectedEndTime > libraryClosingTime) {
                    alert('圖書館晚上10點就閉館囉！');
                    endTimeInput._flatpickr.clear(); // 清空结束时间栏位
                }

                // 时间检查 - 开始时间不能晚于结束时间
                var selectedStartTime = startTimeInput.latestSelectedDateObj;
                if (selectedStartTime && selectedEndTime) {
                    if (selectedStartTime > selectedEndTime) {
                        alert('開始時間不能晚於结束時間。');
                        endTimeInput._flatpickr.clear(); // 清空结束时间栏位
                    }
                }
            }
        });
    });
</script>

<br><br>
           <!-- 在表单中显示座位樓層和插座信息 -->
            <label for="seatfloor">座位樓層:</label>
            <input type="text" id="seatfloor" name="seatfloor" value="<?php echo htmlspecialchars($seatFloor); ?>" readonly><br><br>

            <label for="socket">插座:</label>
            <input type="text" id="socket" name="socket" value="<?php echo htmlspecialchars($socket); ?>" readonly><br><br>

            <input class="edit-button" type="submit" value="預約座位">
            
            <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['starttime']) && isset($_POST['endtime'])) {
                $start_time = $_POST['starttime'];
                $end_time = $_POST['endtime'];
                $seat_name = $_POST['seatname'];
                $seatfloor=$_POST['seatfloor'];
                $socket=$_POST['socket'];
            // Fetch User_Id based on the current user's account
            $user_query = "SELECT User_Id FROM user WHERE User_Account = ?";
            $stmt = $conn->prepare($user_query);
            $stmt->bind_param("s", $_SESSION['account']);
            $stmt->execute();
            $user_result = $stmt->get_result();

            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $user_id = $user_row['User_Id'];

                // Fetch Seat_Id based on the selected seat name
                $seat_query = "SELECT Seat_Id FROM seat WHERE Seat_Name = ?";
                $stmt = $conn->prepare($seat_query);
                $stmt->bind_param("s", $seat_name);
                $stmt->execute();
                $seat_result = $stmt->get_result();

                if ($seat_result->num_rows > 0) {
                    $seat_row = $seat_result->fetch_assoc();
                    $seat_id = $seat_row['Seat_Id'];

                    if (isset($_POST['starttime']) && isset($_POST['endtime'])) {
                        $start_time = $_POST['starttime'];
                        $end_time = $_POST['endtime'];
                        $seat_id = $seat_id; // 获取座位ID
                    
                        // 检查是否有重复预约或时间冲突
                        $check_duplicate_query = "SELECT * FROM reservation 
                            WHERE Seat_Id = ? AND ((Start_Time < ? AND End_Time > ?) OR (Start_Time < ? AND End_Time > ?) OR (Start_Time >= ? AND End_Time <= ?))";
                        $stmt = $conn->prepare($check_duplicate_query);
                        $stmt->bind_param("issssss", $seat_id, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);

                        $stmt->execute();
                        $result = $stmt->get_result();

                    
                        if ($result->num_rows > 0) {
                            echo '<script>alert("預約錯誤！該時段已有人預約或預約時間有重疊。請重新預約!");</script>';
                            echo '<script>';
                            echo 'document.getElementById("starttime").value = "";'; // 清空开始时间
                            echo 'document.getElementById("endtime").value = "";'; // 清空结束时间
                            echo '</script>';
                        } else {
                            // 执行预约
                            $insert_query = "INSERT INTO reservation (Start_Time, End_Time, User_Id, Seat_Id) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                    
                            // 假设 $user_id 是已定义的用户ID变量
                            $stmt->bind_param("ssii", $start_time, $end_time, $user_id, $seat_id);
                    
                            if ($stmt->execute()) {
                                echo '<script>alert("您预约成功了！");</script>';
                                echo '<script>window.location.href = "user_reservation.php";</script>';
                            } else {
                                echo "发生错误: " . $stmt->error;
                            }
                        }
                    
                    } else {
                        echo  '<script>alert("請輸入開始時間和結束時間");</script>';
                    }
                    echo "<script>document.getElementById('seatname').value = '$seat_name';</script>";
                            echo "<script>document.getElementById('seatfloor').value = '$seatfloor';</script>";
                            echo "<script>document.getElementById('socket').value = '$socket';</script>";
                } else {
                    echo '<script>alert("找不到相應的座位名稱");</script>';
                }
            } else {
                echo "找不到相應的使用者帳號";
            }
        } else {
            echo  '<script>alert("請輸入開始時間和結束時間");</script>';
        }
            
        }
        ?>

        </form>
    </div>
</body>

</html>
