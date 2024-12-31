<?php

    // function load_user($username) {
    //     require "db.php";
        
    //     $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
    //     if ($stmt = $link->prepare($sql)) {
    //         $stmt->execute();

    //         $result = $stmt->get_result();
    //         if ($result->num_rows > 0) {
    //             return $result->fetch_assoc(); // 返回关联数组形式的结果
    //         } else {
    //             return null; // 没有找到数据时返回null
    //         }
            
    //         $stmt->close();
    //     } else {
    //         error_log("查詢失敗: " . $link->error);
    //         return null;
    //     }
    // }

    function select_family_information($username) {
        require 'db.php';
        $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
        $result1 = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result1);
        $account_id = $row['id'];

        $sql = "SELECT * FROM `descendants` WHERE `account_id` = '$account_id'";
        $result2 = mysqli_query($link, $sql);

        $familys = [];

        while ($row = mysqli_fetch_assoc($result2)) {
            $binding_code_id = $row['binding_code_id'];
            $descendants_id = $row['id'];
            
            $sql = "SELECT * FROM `user_information` WHERE `id` = '$binding_code_id'";
            $result3 = mysqli_query($link, $sql);
            $rows = mysqli_fetch_assoc($result3);
            
            $sovits_role = $rows['sovits_role'];
            $picture_path = $rows['picture_path'];
            $transfer_video = $rows['transfer_video'];
            $binding_code = $rows['binding_code'];
            $account_id = $rows['account_id'];

            if ($binding_code != NULL) {
                $sql = "SELECT * FROM `account` WHERE `id` = '$account_id'";
                $result4 = mysqli_query($link, $sql);
                $rows = mysqli_fetch_assoc($result4);
                $username = $rows['username'];
    
                $familys[] = [
                    'binding_code_id' => $binding_code_id,
                    'sovits_role' => $sovits_role,
                    'picture_path' => $picture_path,
                    'transfer_video' => $transfer_video,
                    'binding_code' => $binding_code,
                    'username' => $username,
                    'descendants_id' => $descendants_id
                ];
            }
        }
        $link->close();
        return $familys; // 返回填充好的产品数组
    }

    // 尋找family_chat.php顯示的圖片
    function load_fmaily_chat($binding_code) {
        require "db.php";
        
        // 搜尋親人相關資訊
        $sql = "SELECT * FROM `user_information` WHERE `binding_code` = '$binding_code'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $family = [
            'user_information_id' => $row['id'],
            'account_id' => $row['account_id'],
            'sovits_role' => $row['sovits_role'],
            'picture_path' => $row['picture_path'],
            'transfer_video' => $row['transfer_video'],
            'talk_video_folder' => $row['talk_video_folder'],
            'binding_code' => $row['binding_code'],
        ];
        $link->close();
        return $family; // 返回填充好的产品数组
    }

    // 載入開場白(gpt)
    function load_open_gpt($binding_code) {
        require "db.php";

        $open = [];
        
        // 搜尋 user_information_id
        $sql = "SELECT * FROM `user_information` WHERE `binding_code` = '$binding_code'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $user_information_id = $row['id'];

        // 搜尋開場白 prompt
        $sql = "SELECT * FROM `family_chat_prompt` WHERE `user_information_id` = '$user_information_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $link->close();

        return $row; // 返回填充好的产品数组
    }

    function load_prompt($user_information_id) {
        require "db.php";

        // 搜尋開場白 prompt
        $sql = "SELECT * FROM `family_chat_prompt` WHERE `user_information_id` = '$user_information_id'";
        $result = mysqli_query($link, $sql);
        $open = mysqli_fetch_assoc($result);

        return $open;
    }

    // 載入對話紀錄
    function load_chat_history($user_information_id, $username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋對話紀錄
        $sql = "SELECT * FROM `family_chat_history` WHERE `account_id` = '$account_id' AND `user_information_id` = '$user_information_id'";
        $result = mysqli_query($link, $sql);

        $history = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $account_id = $row['account_id'];
            $role = $row['role'];
            $text = $row['text'];
            $video_path = $row['video_path'];

            $history[] = [
                'id' => $id,
                'account_id' => $account_id,
                'role' => $role,
                'text' => $text,
                'video_path' => $video_path
            ];
        }

        $link->close();
        return $history; // 返回填充好的产品数组
    }

?>