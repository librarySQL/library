<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    $mes='';
    $con = new mysqli("localhost", "root", "yourpassword", "圖書館座位預約系統");
    
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    if(isset($_POST['insert'])&& !empty($_POST['account']) && !empty($_POST['password']) 
    && !empty($_POST['DepartureTime']) && !empty($_POST['ArrivalTime']) ){

        $account=$_POST['account'];
        $password=$_POST['password'];
        $DepartureTime=$_POST['DepartureTime'];
        $ArrivalTime=$_POST['ArrivalTime'];

        $sql = "SELECT * FROM user WHERE User_Account='$account'";
        $result = $con->query($sql);

        $duplicate = false;
        while($row = $result->fetch_assoc()){
            if($account == $row["account"]){
                $duplicate=false;    
            }
        }
        if(!$duplicate){
            $newuser="INSER INTO user(User_Account,User_Password,Departure_Time,Arrival_Time)
                        VALUE ('$account','$password','$DepartureTime','$ArrivalTime')";
            $mes="新增成功";
        }

    }
    ?>
    <div class="container" style="width: 700px;margin: 0px auto; top:50px; margin-bottom 200px; font-family:Microsoft JhengHei;">
    <Form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <?php
    for($i=0;$i<5;$i++){
        echo"</br>";
    }
    ?>
    <div align="center"><input type="text" class="form-control" name="account" placeholder="Account"  style="width:30%;height: 40px;"></div><br>
    <div align="center"><input type="password" class="form-control" name="password"  placeholder="Password" style="width:30%;height: 40px;"></div><br>
    <div align="center"><input type="datetime-local" class="form-control" name="DepartureTime" style="width:30%;height: 40px;"></div><br>
    <div align="center"><input type="datetime-local" class="form-control" name="ArrivalTime"  style="width:30%;height: 40px;"></div><br>
    <div align="center"><button class="btn btn-primary" type="submit" name="insert" style="width:30%;height: 40px;">新增使用者</button></div>
    </Form> 

    </div>
</body>
</html>
