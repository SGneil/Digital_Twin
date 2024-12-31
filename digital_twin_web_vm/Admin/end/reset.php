<?php
    require_once 'db.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $binding_code = $_POST['binding_code'];
    $identity = $_POST['identity'];

    $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE `account` SET `identity` = '$identity', `email` = '$email', `binding_code` = '$binding_code', `password` = '$password' WHERE `username` = '$username'";
        $link->query($update_sql);
        $link->close();
        header('Location: ../../Admin/front/management_user.php');
    }
    
    $link->close();
?> 