<?php
    require('db.php');

    function deleteDirectory($dir) {
        // 確認資料夾存在並且是資料夾
        if (!is_dir($dir)) {
            return false;
        }
        
        // 開啟資料夾
        $items = scandir($dir);
        foreach ($items as $item) {
            // 跳過 . 和 ..
            if ($item === '.' || $item === '..') {
                continue;
            }
    
            $path = $dir . DIRECTORY_SEPARATOR . $item;
    
            // 判斷是文件還是資料夾
            if (is_dir($path)) {
                // 如果是資料夾，遞迴呼叫自身刪除資料夾
                deleteDirectory($path);
            } else {
                // 如果是文件，刪除文件
                unlink($path);
            }
        }
        
        // 刪除空資料夾
        return rmdir($dir);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['account_id'])) {
            $account_id = $_GET['account_id'];
            $username = $_GET['username'];

            $delete_sql = "DELETE FROM `account` WHERE `id` = '$account_id'";
            if ($link->query($delete_sql) === TRUE) {
                echo ("刪除成功");
            }
            else {
                echo ("刪除失敗");
            }

            $table_names = ['chats', 'descendants', 'family_chat_history', 'family_chat_prompt', 'picture_information', 'test_chat_history', 'user_information', 'user_provided_information'];

            foreach ($table_names as $table) {
                $delete_sql = "DELETE FROM `$table` WHERE `account_id` = '$account_id'"; // 刪除該資料表的所有資料
                $link->query($delete_sql); // 執行刪除操作
            }

            $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
            $result = mysqli_query($link, $sql);
            $num_rows = mysqli_num_rows($result);

            if ($num_rows > 0) {
                $row = mysqli_fetch_assoc($result);
                $binding_code_id = $row['id'];

                $delete_sql = "DELETE FROM `descendants` WHERE `binding_code_id` = '$binding_code_id'"; // 刪除該資料表的所有資料
                $link->query($delete_sql); // 執行刪除操作
            }

            $link->close();

            $directoryPath = "../../family/{$username}";

            if (deleteDirectory($directoryPath)) {
                echo "資料夾已成功刪除";
            } else {
                echo "資料夾刪除失敗，請檢查路徑是否正確";
            }
        }
        header('Location: ../../Admin/front/management_user.php');
    }
?>
