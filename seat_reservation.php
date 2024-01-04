<?php
session_start();
$conn = new mysqli("localhost", "root", "ccl5266ccl", "圖書館座位預約系統");

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
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
        <h2>預約座位</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="reservationId" value="<?php echo $reservationId; ?>">

            <input type="hidden" id="User_Account" name="User_Account" value="<?php echo $userid; ?>" ><br><br>

            <label for="seatname">座位:</label>
            <input type="text" id="seatname" name="seatname" value="<?php echo htmlspecialchars($_POST['selected_seat'] ?? ''); ?>" readonly><br><br>


            <label for="starttime">開始時間:</label>
            <input type="datetime-local" id="starttime" name="starttime" value="" ><br><br>

            <label for="endtime">結束時間:</label>
            <input type="datetime-local" id="endtime" name="endtime" value="" ><br><br>

            <script>
            // 獲取開始時間和結束時間的 input 元素
            var startTimeInput = document.getElementById('starttime');
            var endTimeInput = document.getElementById('endtime');
            // 当开始时间改变时执行验证
            startTimeInput.addEventListener('change', function() {
            // 获取开始时间的值
            var startTimeValue = new Date(startTimeInput.value);
        
            // 获取当天8:00的时间
             var libraryOpeningTime = new Date();
            libraryOpeningTime.setHours(8, 0, 0, 0); // 设置为当天的8:00
        
            // 验证开始时间不早于图书馆开馆时间（早上8:00）
            if (startTimeValue < libraryOpeningTime) {
            alert('圖書館早上8:00才開館喔！');
            startTimeInput.value = ''; // 清空开始时间字段
            }
            });
            // 當結束時間改變時執行驗證
            endTimeInput.addEventListener('change', function() {
                // 獲取開始時間和結束時間的值
                var startTimeValue = new Date(startTimeInput.value);
                var endTimeValue = new Date(endTimeInput.value);
                // 获取当天 23:00 的时间
                var libraryClosingTime = new Date();
                libraryClosingTime.setHours(22, 0, 0, 0); // 设置为当天的23:00
    
                 // 驗證結束時間不晚於圖書館關閉時間（晚上22:00）
                if (endTimeValue > libraryClosingTime) {
                 alert('圖書館晚上10:00就閉館囉！');
                endTimeInput.value = ''; // 清空結束時間欄位
                }
                // 驗證結束時間不早於開始時間
                else if (endTimeValue < startTimeValue) {
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
                        $seat_id = $_POST['seat_id']; // 假設有 seat_id 作為座位的唯一識別符號
                    
                        // 檢查是否有重複預約或時間衝突
                        $check_duplicate_query = "SELECT * FROM reservation WHERE Seat_Id = ? AND ((Start_Time <= ? AND End_Time >= ?) OR (Start_Time <= ? AND End_Time >= ?))";
                        $stmt = $conn->prepare($check_duplicate_query);
                        $stmt->bind_param("issss", $seat_id, $start_time, $start_time, $end_time, $end_time);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        if ($result->num_rows > 0) {
                            echo '<script>alert("預約錯誤！該時段已被預約或有時間衝突。請重新預約。");</script>';
                            echo '<script>';
                            echo 'document.getElementById("starttime").value = "";'; // 清空開始時間
                            echo 'document.getElementById("endtime").value = "";'; // 清空結束時間
                            echo '</script>';
                        } else {
                            // 執行預約
                            $insert_query = "INSERT INTO reservation (Start_Time, End_Time, User_Id, Seat_Id) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bind_param("ssii", $start_time, $end_time, $user_id, $seat_id);
                    
                            if ($stmt->execute()) {
                                echo '<script>alert("您預約成功了！");</script>';
                                echo '<script>window.location.href = "reservation.php";</script>';
                            } else {
                                echo "發生錯誤: " . $stmt->error;
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
