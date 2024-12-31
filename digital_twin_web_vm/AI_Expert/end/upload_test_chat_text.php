<?php
    session_start();
    require('db.php');
    
    if ($_SERVER['REQUEST_METHOD']){
        // 獲取POST數據
        $text = $_POST['text'];
        $role = $_POST['role'];
        $username = $_SESSION['username'];
    
        // 搜尋 account_id
        $select_sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $select_sql);
        $row = mysqli_fetch_assoc($result);
    
        $account_id = $row['id'];
    
        // 對話存入資料庫
        $insert_sql = "INSERT INTO `test_chat_history` (`account_id`, `role`, `text`) VALUES ('$account_id', '$role', '$text')";
        $result = mysqli_query($link, $insert_sql);
    
        // 搜尋 id
        $select_sql = "SELECT * FROM `test_chat_history` WHERE `account_id` = '$account_id' AND `role` = 'gpt' ORDER BY `time` DESC LIMIT 1";
        $result = mysqli_query($link, $select_sql);
        $row = mysqli_fetch_assoc($result);
    
        $id = $row['id'];

        $link->close();
        echo($id);
    }
    
?>