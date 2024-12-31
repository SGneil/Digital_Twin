<?php

    function load_user($username) {
        require "db.php";
        
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        if ($stmt = $link->prepare($sql)) {
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // 返回关联数组形式的结果
            } else {
                return null; // 没有找到数据时返回null
            }
            
            $stmt->close();
        } else {
            error_log("查詢失敗: " . $link->error);
            return null;
        }
    }

    function select_family_information($username) {
        require 'db.php';
        // $sql = "SELECT * FROM `product`";
        $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $account_id = $row['id'];

        $sql = "SELECT * FROM `picture_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);

        $familys = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['video_state'] == 'doing') {
                $state = '未準備好';
            }
            else if ($row['video_state'] == 'finish') {
                $state = '已準備好';
            }

            $familys[] = [
                'id' => $row['id'],
                'account_id' => $row['account_id'],
                'name' => $row['name'],
                'picture_name' => $row['picture_name'],
                'picture_path' => $row['picture_path'],
                'video_name' => $row['video_name'],
                'video_path' => $row['video_path'],
                'video_state' => $state
            ];
        }
        $link->close();
        return $familys; // 返回填充好的产品数组
    }

    function check_photo($username) {
        require 'db.php';
        // $sql = "SELECT * FROM `product`";
        $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $account_id = $row['id'];

        $sql = "SELECT * FROM `picture_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $row_count = mysqli_num_rows($result);

        if ($row_count == 0) {
            $answer = 'NO';
        }
        else {
            $answer = 'YES';
        }
        
        $link->close();
        return $answer;
    }

    function load_family($username) {
        require "db.php";
        
        // 搜尋account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋使用者圖片相關資料
        $sql = "SELECT * FROM `picture_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['video_state'] == 'doing') {
            $state = '未準備好';
        }
        else if ($row['video_state'] == 'finish') {
            $state = '已準備好';
        }

        $familys = [
            'id' => $row['id'],
            'account_id' => $row['account_id'],
            'name' => $row['name'],
            'picture_name' => $row['picture_name'],
            'picture_path' => $row['picture_path'],
            'video_name' => $row['video_name'],
            'video_path' => $row['video_path'],
            'video_state' => $state
        ];
        $link->close();
        return $familys; // 返回填充好的产品数组
    }

    function load_video_state($username) {
        require "db.php";
        
        // 搜尋account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋使用者圖片相關資料
        $sql = "SELECT * FROM `picture_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);

        $num_rows = mysqli_num_rows($result);

        if ($num_rows <= 0) {
            return '未準備好';
        }

        $row = mysqli_fetch_assoc($result);
        

        if ($row['video_state'] == 'doing') {
            $state = '未準備好';
        }
        else if ($row['video_state'] == 'finish') {
            $state = '已準備好';
        }

        return $state; // 返回填充好的产品数组
    }

    // 尋找family_chat.php顯示的圖片
    function load_fmaily_chat($username) {
        require "db.php";
        
        // 搜尋account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋圖片位置
        $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $user_information_id = $row['id'];
        $account_id = $row['account_id'];
        $sovits_role = $row['sovits_role'];
        $picture_path = $row['picture_path'];
        $transfer_video = $row['transfer_video'];
        $talk_video_folder = $row['talk_video_folder'];
        $binding_code = $row['binding_code'];

        // 搜尋開場白
        $sql = "SELECT * FROM `family_chat_prompt` WHERE `user_information_id` = '$user_information_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $family = [
            'id' => $user_information_id,
            'account_id' => $account_id,
            'sovits_role' => $sovits_role,
            'picture_path' => $picture_path,
            'transfer_video' => $transfer_video,
            'talk_video_folder' => $talk_video_folder,
            'binding_code' => $binding_code,
            'family_chat_prompt_id' => $row['id'],
            'gpt_prompt' => $row['gpt_prompt']
        ];
        $link->close();
        return $family; // 返回填充好的产品数组
    }

    function load_prompt($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋 user_information_id
        $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $user_information_id = $row['id'];


        // 搜尋開場白 prompt
        $sql = "SELECT * FROM `family_chat_prompt` WHERE `user_information_id` = '$user_information_id'";
        $result = mysqli_query($link, $sql);
        $open = mysqli_fetch_assoc($result);

        return $open;
    }

    function load_test_chat_history($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        // 搜尋對話紀錄
        $sql = "SELECT * FROM `test_chat_history` WHERE `account_id` = '$account_id'";
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
        return $history; // 傳回歷史對話紀錄
    }


    function check_step3($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_step4($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['sovits_role'] != 'doing' && $row['transfer_video'] != 'doing') {
                return 'show';
            }
            else {
                return 'hide';
            }
        }
        else {
            return 'hide';
        }
    }

    function check_step4_unlock($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `user_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['sovits_role'] == 'doing' && $row['transfer_video'] == 'doing') {
                return 'all';
            }
            else if ($row['transfer_video'] == 'doing') {
                return 'transfer_video';
            }
            else if ($row['sovits_role'] == 'doing') {
                return 'sovits_role';
            }
        }
        else {
            return 'all';
        }
    }

    function check_step5($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `test_chat_history` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list1($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '1'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list2($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '2'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list3($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '3'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list4($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '4'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list5($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '5'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    function check_list6($username) {
        require "db.php";
        
        // 搜尋 account_id
        $sql = "SELECT * FROM `account` WHERE `username` = '$username'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $account_id = $row['id'];

        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' AND `topic_id` = '6'";
        $result = mysqli_query($link, $sql);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            return 'show';
        }
        else {
            return 'hide';
        }
    }

    

?>