<?php
    session_start();
    function call_get_video_api($imagepath, $audiopath, $resultpath, $folder_name) {
        $url = 'http://localhost:5003/get_video';
        $data = array(
            'video_file' => $imagepath,
            'vocal_file' => $audiopath,
            'output_file' => $resultpath,
            'folder_name' => $folder_name
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imagepath = 'D:/web/123qwe.com/Digital_Twin/front/family/' . ltrim($_POST['imagepath'], './');
        $audiopath = 'D:/web/123qwe.com/Digital_Twin/front/' . ltrim($_POST['audiopath'], './');
        $username = $_SESSION['username'];
        $filename = 'family/' . $username . '/video';
        // 檢查目錄是否存在
        if (!file_exists('D:/web/123qwe.com/Digital_Twin/front/' .$filename)) {
            mkdir('D:/web/123qwe.com/Digital_Twin/front/' . $filename);
        }

        $filename = $filename . '/' . date("YmdHis") . '.mp4';

        // 使用 '/' 分割字符串，并取第二部分
        $parts = explode('/', $_POST['imagepath']);
        $folder_name = $parts[1];

        // 去掉扩展名 '.jpg'
        $folder_name = pathinfo($folder_name, PATHINFO_FILENAME);

        $response = call_get_video_api($imagepath, $audiopath, 'D:/web/123qwe.com/Digital_Twin/front/' . $filename, $folder_name);

        // 解碼從API返回的JSON響應
        $responseData = json_decode($response, true);

        // 獲取API返回的消息
        if (isset($responseData['status'])) {
            // $answer = $responseData['status'];
            // echo $answer;
            echo './' . $filename;
        } else {
            echo 'Error: Invalid response from API';
        }
    }
?>