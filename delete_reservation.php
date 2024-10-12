<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['account'])) {
    $userId = $_SESSION['account'];
} else {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['reservationId'])) {
    $reservationId = $_GET['reservationId'];
    
    // 建立連線
    $con = new mysqli("localhost", "root", "yourpassword", "librarydb");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // 刪除預約
    $deleteQuery = "DELETE FROM reservation WHERE Reservation_Id = '$reservationId'";
    $result = $con->query($deleteQuery);

    if ($result === TRUE && $con->affected_rows > 0) {
        // 刪除成功，返回到原來的頁面
        header("Location: reservation.php");
        exit;
    } else {
        echo "刪除預約時發生錯誤：" . $con->error;
    }

    $con->close();
} else {
    echo "無效的請求";
}
?>
