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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // 获取要删除的座位 ID
    $seatId = $_GET['id'];


    if (!empty($seatId)) {
        // 建立连接
        $con = new mysqli("localhost", "root", "eva65348642", "librarydb");

        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // 使用预处理语句删除座位
        $deleteQuery = "DELETE FROM seat WHERE Seat_Id = ?";
        $statement = $con->prepare($deleteQuery);
        
        if ($statement) {
            $statement->bind_param("s", $seatId);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                // 删除成功，返回到原来的页面
                header("Location: seatdetail.php");
                exit;
            } else {
                echo "刪除座位時發生錯誤：" . $con->error;
            }

            $statement->close();
        } else {
            echo "预处理失败：" . $con->error;
        }

        $con->close();
    } else {
        echo "座位 ID 未指定";
    }
} else {
    echo "無效的請求";
}
?>
