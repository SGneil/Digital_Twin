<?php
    require("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $account_id = $_POST['account_id'];
        
        // 檢查是否有傳入 sovits_role
        if (isset($_POST['sovits_role'])) {
            $sovits_role = $_POST['sovits_role'];
            $update_mode = true;
        } else {
            $update_mode = false;
        }

        $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
        $result = $link->query($sql);

        if ($result->num_rows == 0) {
            // 如果用戶不存在，且是更新模式才插入新記錄
            if ($update_mode) {
                $insert_sql = "INSERT INTO `user_information` (`account_id`, `sovits_role`) VALUES ('$account_id', '$sovits_role')";
                $link->query($insert_sql);
                echo "新用戶資料已插入";
            } else {
                echo "用戶不存在";
            }
        } else {
            // 如果用戶存在
            $row = $result->fetch_assoc();
            
            // 如果是更新模式，則更新資料
            if ($update_mode) {
                $update_sql = "UPDATE `user_information` SET `sovits_role` = '$sovits_role' WHERE `account_id` = '$account_id'";
                $link->query($update_sql);
                echo "用戶資料已更新";
            } else {
                // 只有在非更新模式時才回傳 sovits_role
                echo $row['sovits_role'];
            }
        }
        
        $link->close();
    }
    else {
        echo("請使用 POST 方法訪問此頁面");
    }
?>
