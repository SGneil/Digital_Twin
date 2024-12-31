<?php
    session_start();

    function call_get_gpt_text_api($text) {
        require("port.php");

        $target_url = "http://{$php_api_vm_port}/digital_twin/WEB_Server/ChatGPT/receive_files.php"; // 服务器B的接收文件URL
    
        $post = array(
            'text' => $text,
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
            return($response);
        }
    
        curl_close($ch);
    }

    // 創建對應的轉換函數
    function transformData($data, $user_prompt, $gpt_prompt) {
        $result = [];
        $result[] = [
            'role' => 'user',
            'content' => $user_prompt
        ];

        $result[] = [
            'role' => 'assistant',
            'content' => $gpt_prompt
        ];

        foreach ($data as $entry) {
            if ($entry['role'] === 'user') {
                $result[] = [
                    'role' => 'user',
                    'content' => $entry['text']
                ];
            } elseif ($entry['role'] === 'gpt') {
                $result[] = [
                    'role' => 'assistant',
                    'content' => $entry['text']
                ];
            }
        }

        return $result;
    }

    function total_text_to_prompt($user_information_id, $username) {

        require("./function.php");
        $open_user = load_prompt($user_information_id);

        $chat_history = load_chat_history($user_information_id, $username);


        // 轉換資料
        $transformedData = transformData($chat_history, $open_user['user_prompt'], $open_user['gpt_prompt']);

        // 將結果轉換為 JSON 字串
        $jsonString = json_encode($transformedData, JSON_UNESCAPED_UNICODE);

        return $jsonString;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // $msg = $_POST['msg']; 用不到
        $username = $_SESSION['username'];
        $user_information_id = $_POST['user_information_id'];

        // 整理成 chatgpt 需要的提示詞
        $prompt = total_text_to_prompt($user_information_id, $username);

        $response = call_get_gpt_text_api($prompt);

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
