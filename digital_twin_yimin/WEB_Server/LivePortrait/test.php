<?php
    require('port.php');
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

    sent_video_to_web('yimin20240930093353', '/var/www/html/digital_twin/WEB_Server/family/yimin20240930093353/input/video/yimin20240930093353.mp4');
?>