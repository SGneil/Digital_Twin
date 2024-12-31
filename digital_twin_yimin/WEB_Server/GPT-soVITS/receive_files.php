<?php 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $text = $_POST['text'];
        $sovits_role = $_POST['sovits_role'];
        $video_name = $_POST['video_name'];
        $save_video_path = $_POST['save_path'];

        $save_audio_path = "../../WEB_Server/family/$video_name/input/sovits/$video_name.wav";

        get_audio($text, $sovits_role, $save_audio_path);

        // 無限迴圈檢查檔案是否生成
        while (!file_exists($save_audio_path)) {
            sleep(1); // 每秒檢查一次
        }

        sent_video_to_web($save_audio_path, $save_video_path);

    }
    else {
        
        echo("你進來的方式不對~");
    }


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

    // 傳送生成的音檔到web
    function sent_video_to_web($audio_path, $save_path) {
        require('port.php');

        $target_url = "http://{$php_web_vm_port}/digital_twin/FamiliaLyrica/end/receive_sovits_audio.php"; // 服务器A的接收文件URL

        // 檢查影片檔案是否存在
        if (!file_exists($audio_path)) {
            die("Audio file does not exist: $audio_path");
        }

        $audio_file = curl_file_create($audio_path);

        $post = array(
            'save_path' => $save_path,
            'audio' => $audio_file
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
            if (file_exists($audio_path)) {
                unlink($audio_path);
            }
            echo $response;
        }
        
        curl_close($ch);
    }
?>