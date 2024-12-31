<?php
    // 傳送遷移影片及對話文字到api伺服器

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text = $_POST['text'];
        $sovits_role = $_POST['sovits_role'];
        $video_path = $_POST['video_path'];
        $save_path = $_POST['talk_video_folder'];
        $type = $_POST['type'];
        $id = $_POST['id'];

        upload_video_waiting($type, $id);
        
        $response = sent_to_api_server($text, $sovits_role, $video_path, $save_path);

        upload_video_path($type, $id, $response);
    }

    // 傳送 api
    function sent_to_api_server($text, $sovits_role, $video_path, $save_path) {
        require("port.php");

        $target_url = "http://{$php_api_vm_port}/digital_twin/WEB_Server/Easy-Wav2Lip/receive_files.php"; // 服务器B的接收文件URL
        // $target_url = 'http://35.206.227.52/web/digital_twin/Easy-Wav2Lip/receive_files.php'; // 服务器B的接收文件URL
    
        if (!file_exists($video_path)) {
            echo "Video file does not exist: $video_path\n";
            return;
        }

        // 使用 basename 函數來取得檔案名稱（包含副檔名）
        $fileWithExtension = basename($video_path);

        // 使用 pathinfo 函數來取得檔案名稱（不含副檔名）
        $video_name = pathinfo($fileWithExtension, PATHINFO_FILENAME);

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

    // 修改路徑
    function convertPath($aPath) {
        // 使用正則表達式匹配並替換路徑
        $pattern = '/\.\.\/\.\.\/family\/([^\/]+)\/input\/video\//';
        $replacement = '/family/$1/output/results/';
        $bPath = preg_replace($pattern, $replacement, $aPath);
    
        return $bPath;
    }

    function upload_video_waiting($type, $id) {
        require('db.php');

        if ($type == 'open') {
            $update_sql = "UPDATE `family_chat_prompt` SET `video_path` = 'waiting' WHERE `id` = '$id'";
            $result = mysqli_query($link, $update_sql);
        }
        else {
            $update_sql = "UPDATE `test_chat_history` SET `video_path` = 'waiting' WHERE `id` = '$id'";
            $result = mysqli_query($link, $update_sql);
        }
    }

    function upload_video_path($type, $id, $path) {
        require('db.php');

        if ($type == 'open') {
            $update_sql = "UPDATE `family_chat_prompt` SET `video_path` = '$path' WHERE `id` = '$id'";
            $result = mysqli_query($link, $update_sql);
        }
        else {
            $update_sql = "UPDATE `test_chat_history` SET `video_path` = '$path' WHERE `id` = '$id'";
            $result = mysqli_query($link, $update_sql);
        }
    }
?>
