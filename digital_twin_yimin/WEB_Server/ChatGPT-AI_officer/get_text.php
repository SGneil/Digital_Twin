<?php
    function call_get_gpt_text_text($text) {
        require("port.php");

        $target_url = "http://localhost:5002/get_gpt_text"; // 服务器B的接收文件URL
    

        // 接收來自前端的資料
        $inputData = file_get_contents('php://input');
        $messages = json_decode($inputData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }

        // 初始化 CURL
        $ch = curl_init($target_url);

        // 設定 CURL 選項
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

        // 發送請求並獲取回應
        $response = curl_exec($ch);

        // 檢查 CURL 錯誤
        if ($response === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to contact']);
            exit;
        }

        // 關閉 CURL
        curl_close($ch);

        // 將 OpenAI 的回應傳回前端
        echo $response;

    }
?>
