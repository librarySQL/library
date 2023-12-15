<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<?php
    session_start();
    unset($_SESSION['login']);
    unset($_SESSION['User_ID']);
    unset($_SESSION['User_Account']);
    unset($_SESSION['User_Password']);
    header("Location: Login.php");
?>
</body>
</html>