<?php
    session_start();
    require('db.php');
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $account_id = $row['id'];
    $binding_code = $row['binding_code'];

    $update_sql = "UPDATE `user_information` SET `binding_code` = '$binding_code' WHERE `account_id` = '$account_id'";
    $link->query($update_sql);
    $link->close();
?>