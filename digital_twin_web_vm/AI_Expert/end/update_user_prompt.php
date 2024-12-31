<?php
    require("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $account_id = $_POST['account_id'];
        $user_information_id = $_POST['user_information_id'];
        $user_prompt = $_POST['user_prompt'];
        $gpt_prompt = $_POST['gpt_prompt'];

        $sql = "SELECT * FROM `family_chat_prompt` WHERE `account_id` = '$account_id'";
        $result = $link->query($sql);

        if ($result->num_rows == 0) {
            // 如果用戶不存在，插入新記錄
            $insert_sql = "INSERT INTO `family_chat_prompt` (`account_id`, `user_information_id`, `user_prompt`, `gpt_prompt`) VALUES ('$account_id', '$user_information_id', '$user_prompt', '$gpt_prompt')";
            $link->query($insert_sql);
            echo "新用戶資料已插入";
        } else {
            // 如果用戶存在，更新記錄
            $update_sql = "UPDATE `family_chat_prompt` SET `user_prompt` = '$user_prompt', `gpt_prompt` = '$gpt_prompt' WHERE `account_id` = '$account_id'";
            $link->query($update_sql);
            echo "用戶資料已更新";
        }
        
        $link->close();
    }
    else {
        echo("請使用 POST 方法訪問此頁面");
    }
?>
