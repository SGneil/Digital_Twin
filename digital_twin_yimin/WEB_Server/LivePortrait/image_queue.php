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
            $sql = "SELECT * FROM `action_transfer` WHERE `status` ='NO' LIMIT 1";
            $result = $link->query($sql);
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $user_name = $row['user_name'];
                $image = $row['image'];
                $driving = $row['driving'];
                $folder = '../../WEB_Server/';

                $image_path = "{$folder}/family/{$user_name}/input/image/{$image}";
                $driving_path = "{$folder}/family/{$user_name}/input/driving/{$driving}";
                $result_folder = "{$folder}/family/{$user_name}/input/video";

                get_action_transfer_video($image_path, $driving_path, $result_folder);

                while (!file_exists("{$result_folder}/{$user_name}.mp4")) {
                    sleep(1); // 每秒檢查一次
                }

                // 更新数据库状态为已处理
                $update_sql = "UPDATE `action_transfer` SET `status` = 'YES' WHERE `user_name` = '$user_name'";

                // 加上浮水印
                add_watermark("{$result_folder}/{$user_name}.mp4", "{$result_folder}/{$user_name}_watermark.mp4");

                while (!file_exists("{$result_folder}/{$user_name}_watermark.mp4")) {
                    sleep(1); // 每秒檢查一次
                }

                unlink("{$result_folder}/{$user_name}.mp4");

                while (file_exists("{$result_folder}/{$user_name}.mp4")) {
                    sleep(1); // 每秒檢查一次
                }

                rename("{$result_folder}/{$user_name}_watermark.mp4", "{$result_folder}/{$user_name}.mp4");

                while (!file_exists("{$result_folder}/{$user_name}.mp4")) {
                    sleep(1); // 每秒檢查一次
                }

                // 傳回前端網頁
                sent_video_to_web($user_name, "{$result_folder}/{$user_name}.mp4");

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

    // 呼叫python api(動作遷移)
    function get_action_transfer_video($image, $driving, $result) {
        $url = 'http://localhost:5001/run_action_transfer';
        $data = array(
            'image' => $image,
            'driving' => $driving,
            'result' => $result
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            die('Error');
        }
        return $result;
    }

    // 加上浮水印
    function add_watermark($video_path, $result_path) {
        $url = 'http://localhost:5006/run_watermark';
        $data = array(
            'video_path' => $video_path,
            'result_path' => $result_path
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            die('Error');
        }
        return $result;
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

    // 傳回前端網頁
    function sent_video_to_web($user_name, $video_path) {
        require('port.php');
        
        $target_url = "http://{$php_web_vm_port}/digital_twin/AI_Expert/end/receive_action_transfer.php"; // 服务器A的接收文件URL

        // $target_url = 'http://35.206.239.57/web/digital_twin/AI_Expert/end/receive_action_transfer.php'; // 服务器A的接收文件URL

        $video_file = curl_file_create($video_path);

        $post = array(
            'user_name' => $user_name,
            'video' => $video_file
        );
    
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
    }

    // 主逻辑：执行处理视频的函数
    process_videos();
?>
