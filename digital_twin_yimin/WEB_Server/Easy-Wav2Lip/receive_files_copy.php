<?php 

    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $text = '你好我只是蛋頭博士';
        $sovits_role = 'leong';
        $video_name = 'leong20241101134016';
        // $save_video_path = $_POST['save_path'];
        $driving_path = "../../WEB_Server/family/$video_name/input/video/$video_name.mp4";
        $save_audio_path = "../../WEB_Server/family/$video_name/input/sovits/$video_name.wav";
        $result_video_path = "../../WEB_Server/family/$video_name/output/results/$video_name.mp4";

        if (file_exists($save_audio_path)) {
            unlink($save_audio_path);
        }

        if (file_exists($result_video_path)) {
            unlink($result_video_path);
        }

        get_audio($text, $sovits_role, $save_audio_path);

        // 無限迴圈檢查檔案是否生成
        while (!file_exists($save_audio_path)) {
            sleep(1); // 每秒檢查一次
        }

        get_video($video_name, $driving_path, $save_audio_path);

        // 無限迴圈檢查檔案是否生成
        while (!file_exists($result_video_path)) {
            sleep(1); // 每秒檢查一次
        }

        // sent_video_to_web($result_video_path, $save_video_path);

        if (file_exists($save_audio_path)) {
            unlink($save_audio_path);
        }

        if (file_exists($result_video_path)) {
            unlink($result_video_path);
        }

    // }
    // else {
        
    //     echo("你進來的方式不對~");
    // }


    // 呼叫 sovits
    function get_audio($text, $sovits_role, $save_audio_path) {
        $url = 'http://localhost:5003/get_family_audio';
        $data = array(
            'text' => $text,
            'sovits_role' => $sovits_role,
            'save_audio_path' => $save_audio_path
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

    // 呼叫 easy-wav2lip
    function get_video($user_name, $video_name, $audio_name) {
        $url = 'http://localhost:5005/get_video';
        $data = array(
            'user_name' => $user_name,
            'video_path' => $video_name,
            'audio_path' => $audio_name
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

    // 傳送生成的說話影片到web
    function sent_video_to_web($video_path, $save_path) {
        require('port.php');

        $target_url = "http://{$php_web_vm_port}/digital_twin/AI_Expert/end/receive_talk_video.php"; // 服务器A的接收文件URL

        // 檢查影片檔案是否存在
        if (!file_exists($video_path)) {
            die("Video file does not exist: $video_path");
        }

        $video_file = curl_file_create($video_path);

        $post = array(
            'save_path' => $save_path,
            'video' => $video_file
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            if (file_exists($video_path)) {
                unlink($video_path);
            }
            echo $response;
        }
        
        curl_close($ch);
    }
?>