<?php
    session_start();
    require('db.php');
    
    if ($_SERVER['REQUEST_METHOD']){
        // account_id
        $username = $_SESSION['username'];
    
        $select_sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $select_sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $answer = 0;
        // 搜尋 gpt_prompt
        $select_sql = "SELECT * FROM `family_chat_prompt` WHERE `account_id` = '$account_id' AND `video_path` = 'waiting'";
        $result = mysqli_query($link, $select_sql);
        $row_count = mysqli_num_rows($result);
        $answer += $row_count;

        // 搜尋 text
        $select_sql = "SELECT * FROM `test_chat_history` WHERE `account_id` = '$account_id' AND `role` = 'gpt' AND `video_path` = 'waiting'";
        $result = mysqli_query($link, $select_sql);
        $row_count = mysqli_num_rows($result);
        $answer += $row_count;

        echo $answer;
    }
    
?>