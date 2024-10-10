<?php
session_start();
$con = new mysqli("localhost", "root", "yourpassword", "libdb");  

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$selected_seat = ""; // 初始化選中的座位變數

// 檢查是否有選擇座位
if (isset($_POST['seatname']) && !empty($_POST['seatname'])) {
    $selected_seat = $_POST['seatname'];

    $current_date_time = date('Y-m-d H:i:s'); // 當前日期和時間

    $check_reservation_query = "SELECT r.Start_Time, r.End_Time, s.Seat_Name, s.Seat_Floor, s.Socket
    FROM reservation r
    INNER JOIN seat s ON r.Seat_Id = s.Seat_Id
    WHERE s.Seat_Name = '$selected_seat' 
    AND ((r.Start_Time >= '$current_date_time') OR (r.End_Time >= '$current_date_time'))
    ORDER BY r.Start_Time ASC";

    $reservation_result = $con->query($check_reservation_query);

    if ($reservation_result->num_rows > 0) {
        // 以表格方式構建預約資訊
        $output = '<table border="1">';
        $output .= '<tr><th>Start Time</th><th>End Time</th><th>Seat Name</th><th>Seat Floor</th><th>Socket</th></tr>';

        while ($row = $reservation_result->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . $row['Start_Time'] . '</td>';
            $output .= '<td>' . $row['End_Time'] . '</td>';
            $output .= '<td>' . $row['Seat_Name'] . '</td>';
            $output .= '<td>' . $row['Seat_Floor'] . '</td>';
            $output .= '<td>' . $row['Socket'] . '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';

        echo $output;
    } else {
        echo "此座位目前尚未被預約";
    }
} else {
    echo "沒有選擇座位";
}
?>
