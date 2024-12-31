<?php
    session_start();
    require('db.php');
    
    if ($_SERVER['REQUEST_METHOD']){
        // 獲取POST數據
        $type = $_POST['type'];
        $id = $_POST['id'];
    
        $answer = '';
        if ($type == 'open') {
            // 搜尋 gpt_prompt
            $select_sql = "SELECT * FROM `family_chat_prompt` WHERE `id` = '$id'";
            $result = mysqli_query($link, $select_sql);
            $row = mysqli_fetch_assoc($result);

            $answer = $row['video_path'];
        }
        else {
            // 搜尋 text
            $select_sql = "SELECT * FROM `family_chat_history` WHERE `id` = '$id'";
            $result = mysqli_query($link, $select_sql);
            $row = mysqli_fetch_assoc($result);

            $answer = $row['video_path'];
        }
        echo $answer;
    }
    
?>