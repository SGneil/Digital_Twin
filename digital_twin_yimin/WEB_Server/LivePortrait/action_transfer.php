<?php
    // 接收前端傳送的圖片及遷移影片

    require('db.php');

    // 建立存放影片、照片、音檔資料夾
    function add_folder($user_name) {
        $user_folder = "../family/{$user_name}";
        $input_folder = "../family/{$user_name}/input/";
        $output_folder = "../family/{$user_name}/output/";
        $image_target_dir = "../family/{$user_name}/input/image/";
        $audio_target_dir = "../family/{$user_name}/input/audio/";
        $sovits_target_dir = "../family/{$user_name}/input/sovits/";
        $video_target_dir = "../family/{$user_name}/input/video/";
        $driving_target_dir = "../family/{$user_name}/input/driving/";
        $result_target_dir = "../family/{$user_name}/output/results/";
        $detected_face_dir = "../family/{$user_name}/output/detected_face/";
    
        $dirs = [
            $user_folder,
            $input_folder,
            $output_folder,
            $image_target_dir,
            $audio_target_dir,
            $video_target_dir,
            $driving_target_dir,
            $result_target_dir,
            $detected_face_dir,
            $sovits_target_dir
        ];
        umask(0000);
        foreach ($dirs as $dir) {
            mkdir($dir, 0777);
        }
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user_name = $_POST['user_name'];
        $family_id = $_POST['family_id'];
        add_folder($user_name);
        
        $image_target_dir = "../family/{$user_name}/input/image/"; // 图片文件保存路径
        $driving_target_dir = "../family/{$user_name}/input/driving/"; // 视频文件保存路径

        if (isset($_FILES['image']) && isset($_FILES['video'])) {
            $image_target_file = $image_target_dir . $user_name . ".jpg";
            $driving_target_file = $driving_target_dir . $user_name . ".mp4";
    
            $image_upload_success = move_uploaded_file($_FILES['image']['tmp_name'], $image_target_file);
            $driving_upload_success = move_uploaded_file($_FILES['video']['tmp_name'], $driving_target_file);
            // echo($image_target_file);
            // echo($driving_target_file);
        }

        // 上傳到資料庫
        $image_name = "{$user_name}.jpg";
        $driving_name = "{$user_name}.mp4";
        $sql = "INSERT INTO `action_transfer` (`family_id`, `user_name`, `image`, `driving`) VALUES ('$family_id', '$user_name', '$image_name', '$driving_name')";
        if ($link->query($sql) === TRUE) {
            
            if (should_stop()) {
                $filename = 'stop.txt'; // 文件名
                $new_content = '1'; // 新的文件内容
                // 将新内容写入文件
                file_put_contents($filename, $new_content);

                require('port.php');

                // 初始化 cURL
                $ch = curl_init();
                // 设置 cURL 选项
                curl_setopt($ch, CURLOPT_URL, "http://{$php_api_vm_port}/digital_twin/WEB_Server/LivePortrait/image_queue.php");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); // 设置为更长时间，比如 2000 毫秒（2秒）

                // 执行 cURL 请求
                $response = curl_exec($ch);
                // echo('456');
                // 检查是否有错误
                if ($response === false) {
                    echo ('cURL 错误: ' . curl_error($ch));
                } else {
                    echo ('请求成功，响应内容: ' . $response);
                }

                // 关闭 cURL 会话
                curl_close($ch);

            }
            
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }

    }
    else {
        
        echo("你進來的方式不對~");
    }
?>
