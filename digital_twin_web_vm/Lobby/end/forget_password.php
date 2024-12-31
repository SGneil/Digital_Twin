<?php
    require_once 'db.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `account` WHERE `username` = '$username' AND `email` = '$email'";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE `account` SET `password` = '$password' WHERE `username` = '$username' AND `email` = '$email'";
        $link->query($update_sql);
        $link->close();
        header('Location: ../front/login_page.php');
        // echo("A password reset link has been sent to your email.");
    } else {
        $link->close();
        header('Location: ../front/forget_password_page.php');
        // echo("No account found with that email address.");
    }
    
    $link->close();
?> 