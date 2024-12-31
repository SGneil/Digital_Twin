<?php
    // 傳送 api
    function sent_to_api_server() {
        require("port.php");

        $target_url = "http://{$php_api_vm_port}/digital_twin/WEB_Server/Easy-Wav2Lip/receive_files.php"; // 服务器B的接收文件URL
    
        $text = '我只是蛋頭博士';
        $sovits_role = 'leong';
        $video_name = 'leong20241101134016';
        $save_path = '/home/neil47111202/digital_twin/family/leong/output/results';

        $post = array(
            'text' => $text,
            'sovits_role' => $sovits_role,
            'video_name' => $video_name,
            'save_path' => $save_path,
        );
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        if ($response === false) {
            echo 'Error: ' . curl_error($ch) . "\n";
        } else {
            // echo ($save_path);
            echo($response);
        }
    
        curl_close($ch);
        return $response;
    }

    sent_to_api_server();
?>
