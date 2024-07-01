<?php
    session_start();
    require('db.php');
    $msg = $_POST['msg'];
    $user = $_POST['user'];
    $relative_picture_path = $_POST['family_picture'];
    $username = $_SESSION['username'];
    
    $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];

    $sql = "SELECT `id` FROM `relative_information` WHERE `relative_picture_path` = '$relative_picture_path'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $family_id = $row['id'];

    if ($user == 'user2') {
        $sql = "INSERT INTO `family_chat` (`user_id`, `family_id`, `msg`, `who_sent`) VALUES ('$user_id', '$family_id', '$msg', '$username')";
        $result = mysqli_query($link, $sql);
    }
    else {
        $sql = "INSERT INTO `family_chat` (`user_id`, `family_id`, `msg`, `who_sent`) VALUES ('$user_id', '$family_id', '$msg', '$relative_picture_path')";
        $result = mysqli_query($link, $sql);
    }
?>