<?php
    // 主循环，用于处理视频或其他任务
    function process_videos() {
        require('db.php');
        while (true) {
            // 检查是否需要停止
            if (should_stop()) {
                echo "Stopping the process according to stop.txt\n";
                break;
            }
            // 查询待处理的视频信息或其他任务
            $sql = "SELECT * FROM `relative_information` WHERE `video_state` ='NO' LIMIT 1";
            $result = $link->query($sql);
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $picture_name = $row['picture_name'];
                $relative_picture_path = $row['relative_picture_path'];
                $relative_video_name = $row['relative_video_name'];
                $relative_video_path = $row['relative_video_path'];

                get_move_body_video();

                // 更新数据库状态为已处理
                $update_sql = "UPDATE `relative_information` SET `video_state` = 'YES' WHERE `picture_name` = '$picture_name'";
                $result = $link->query($update_sql);
            }
            else {
                $filename = 'stop.txt'; // 文件名
                $new_content = '0'; // 新的文件内容
                // 将新内容写入文件
                file_put_contents($filename, $new_content);
            }
        }
        $link->close();
    }

    // 检查是否应该停止的函数
    function should_stop() {
        $stop_file = 'stop.txt';
        if (file_exists($stop_file)) {
            $content = trim(file_get_contents($stop_file));
            return $content === '0'; // 如果 stop.txt 内容为 '0'，返回 true 表示应该停止
        }
        return false; // 默认情况下继续执行
    }

    // 处理数据的函数
    function get_move_body_video() {
        require('db.php'); // 引入数据库连接
        $sql = "SELECT * FROM `relative_information` WHERE `video_state` ='NO' LIMIT 1";
        $result = $link->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $picture_name = $row['picture_name'];
            $relative_picture_path = $row['relative_picture_path'];
            $relative_video_name = $row['relative_video_name'];
            $relative_video_path = $row['relative_video_path'];

            $post_data = array(
                'image' => $relative_picture_path,
                'driving' => 'driving.mp4',
                'result' => $relative_video_path
            );

            // 使用curl在后台调用move_body.php
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost/Digital_Twin/end/move_body.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

            // 更新数据库中视频状态为已处理
            $update_sql = "UPDATE `relative_information` SET `video_state` = 'YES' WHERE `picture_name` = '$picture_name'";
            $link->query($update_sql);
        }

        // 关闭数据库连接
        $link->close();
    }

    // 主逻辑：执行处理视频的函数
    process_videos();
?>
