<?php
    function call_get_gpt_text_api($msg) {
        $url = 'http://localhost:5001/get_gpt_text';
        $data = array('msg' => $msg);

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

        $response = call_get_gpt_text_api($msg);

        // 解碼從API返回的JSON響應
        $responseData = json_decode($response, true);

        // 獲取API返回的消息
        if (isset($responseData['status'])) {
            $answer = $responseData['status'];
            echo $answer;
        } else {
            echo 'Error: Invalid response from API';
        }
    }
?>
