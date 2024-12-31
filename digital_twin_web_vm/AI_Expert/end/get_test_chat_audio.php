<?php
    // 傳送遷移影片及對話文字到api伺服器

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text = $_POST['text'];
        $sovits_role = $_POST['sovits_role'];
        $video_path = $_POST['video_path'];
        $save_path = $_POST['talk_video_folder'];

        // echo($text . '|');
        // echo($sovits_role . '|');
        // echo($video_path . '|');
        // echo($save_path . '|');

        sent_to_api_server($text, $sovits_role, $video_path, $save_path);
    }
    // $text = '你好';
    // $sovits_role = 'YanHua';
    // $video_path = '../../family/test/input/video/test20241001142338.mp4';
    // $save_path = '/family/test/output/results';

    // sent_to_api_server($text, $sovits_role, $video_path, $save_path);

    // 傳送 api
    function sent_to_api_server($text, $sovits_role, $video_path, $save_path) {
        require("port.php");

        $target_url = "http://{$php_api_vm_port}/digital_twin/WEB_Server/GPT-soVITS/receive_files.php"; // 服务器B的接收文件URL
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
    }

    // 修改路徑
    function convertPath($aPath) {
        // 使用正則表達式匹配並替換路徑
        $pattern = '/\.\.\/\.\.\/family\/([^\/]+)\/input\/video\//';
        $replacement = '/family/$1/output/results/';
        $bPath = preg_replace($pattern, $replacement, $aPath);
    
        return $bPath;
    }
?>
