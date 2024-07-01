<?php
    session_start();
    function call_get_audio_api($role, $msg, $filename) {
        $url = 'http://localhost:5002/get_audio';
        $data = array(
            'text' => $msg,
            'user' => $role,
            'filename' => $filename
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
        $msg = $_POST['msg'];
        $user = $_POST['user'];
        $family_picture_path = $_POST['family_picture'];
        $username = $_SESSION['username'];
        $filename = 'family/' . $username . '/audio';
        // 檢查目錄是否存在
        if (!file_exists('D:/web/123qwe.com/Digital_Twin/front/' .$filename)) {
            mkdir('D:/web/123qwe.com/Digital_Twin/front/' . $filename);
        }

        $filename = $filename . '/' . date("YmdHis") . '.wav';

        if ($user == 'user2') {
            $role = 'user2';
        }
        else {
            $role = 'user1';
        }

        $response = call_get_audio_api($role, $msg, 'D:/web/123qwe.com/Digital_Twin/front/' . $filename);

        // 解碼從API返回的JSON響應
        $responseData = json_decode($response, true);

        // 獲取API返回的消息
        if (isset($responseData['status'])) {
            $answer = $responseData['status'];
            echo './' . $filename;
        } else {
            echo 'Error: Invalid response from API';
        }
    }
?>