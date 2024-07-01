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

        $sql = "SELECT * FROM `relative_information` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);

        $familys = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['video_state'] == 'NO') {
                $state = '未準備好';
            }
            else if ($row['video_state'] == 'YES') {
                $state = '已準備好';
            }

            $familys[] = [
                'id' => $row['id'],
                'account_id' => $row['account_id'],
                'relative_name' => $row['relative_name'],
                'relative_relation' => $row['relative_relation'],
                'picture_name' => $row['picture_name'],
                'relative_picture_path' => $row['relative_picture_path'],
                'relative_video_path' => $row['relative_video_path'],
                'video_state' => $state
            ];
        }
        $link->close();
        return $familys; // 返回填充好的产品数组
    }

    function load_family($family_id) {
        require "db.php";
        
        $sql = "SELECT * FROM `relative_information` WHERE `id` = '$family_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['video_state'] == 'NO') {
            $state = '未準備好';
        }
        else if ($row['video_state'] == 'YES') {
            $state = '已準備好';
        }

        $familys = [
            'id' => $row['id'],
            'account_id' => $row['account_id'],
            'relative_name' => $row['relative_name'],
            'relative_relation' => $row['relative_relation'],
            'picture_name' => $row['picture_name'],
            'relative_picture_path' => $row['relative_picture_path'],
            'relative_video_path' => $row['relative_video_path'],
            'video_state' => $state
        ];
        $link->close();
        return $familys; // 返回填充好的产品数组
    }

?>