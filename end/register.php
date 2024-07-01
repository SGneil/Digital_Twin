<?php
    require_once 'db.php';

    // 獲取表單提交的用戶名、密碼、gmail
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];

    $sql = "SELECT * FROM `account` WHERE `username` ='$username'";
    //執行sql
    $result = mysqli_query($link, $sql);
    //返回一個數值
    $rows=mysqli_num_rows($result);

    if (!$rows) {
        $sql = "INSERT INTO `account` (`username`, `password`, `email`, `phone`, `birthday`) VALUES ('$username', '$password', '$email', '$phone', '$birthday')";

        if ($link->query($sql) === TRUE) {
            // echo ("New record created successfully");
            $link->close();
            mkdir('../front/family/' . $username . '/');
            header('Location: ../front/login_page.php');
        }
        
    }
    else {
        header('Location: ../front/register_page.php?msg=1');
    }
?>