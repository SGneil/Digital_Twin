<?php
require_once 'db.php';

// 獲取表單提交的用戶名、密碼、gmail
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

$sql = "SELECT * FROM `account` WHERE `username` ='$username'";
//執行sql
$result = mysqli_query($link, $sql);
//返回一個數值
$rows = mysqli_num_rows($result);

if (!$rows) {
    $sql = "INSERT INTO `account` (`username`, `password`, `email`) VALUES ('$username', '$password', '$email')";

    if ($link->query($sql) === TRUE) {
        // echo ("New record created successfully");
        $link->close();
        add_user_folder($username);
        header('Location: ../front/login_page.php?msg=2');
    }
} else {
    header('Location: ../front/register_page.php?msg=1');
}

// 新增用戶資料夾，用於存放遷移照片、音檔
function add_user_folder($username) {
    if (!is_dir("../../family/")) {
        mkdir("../../family/");
    }
    umask(0000);
    mkdir("../../family/{$username}/");
    mkdir("../../family/{$username}/input/");
    mkdir("../../family/{$username}/input/sovits");
    mkdir("../../family/{$username}/input/image");
    mkdir("../../family/{$username}/input/audio/");
    mkdir("../../family/{$username}/input/video/");
    mkdir("../../family/{$username}/output/");
    mkdir("../../family/{$username}/output/results");
}
?>
