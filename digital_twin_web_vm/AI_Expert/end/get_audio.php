<?php

function sendRequest($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    if ($result === false) {
        throw new Exception("Error Processing Request: " . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

require("port.php");
$url = "http://{$python_api_vm_port}:5003/get_audio";

// 確保接收到的資料是 JSON 格式
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit();
}

$data['returnAudio'] = true;

try {
    $response = sendRequest($url, $data);
    $responseData = json_decode($response, true);

    // 添加這行來記錄完整的響應
    error_log("Python API response: " . print_r($responseData, true));

    if (isset($responseData['status']) && $responseData['status'] === 'OK') {
        if (isset($responseData['audio'])) {
            $audioData = base64_decode($responseData['audio']);
            if ($audioData === false) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to decode audio data']);
                exit();
            }
            // 將音訊數據編碼為 base64 並作為 JSON 返回
            echo json_encode([
                'status' => 'success',
                'audio' => base64_encode($audioData)
            ]);
            exit();
        } else {
            error_log("Audio data not found in response");
            echo json_encode(['status' => 'error', 'message' => 'Audio data not found']);
        }
    } else {
        error_log("Unexpected response status: " . ($responseData['status'] ?? 'unknown'));
        echo json_encode(['status' => 'error', 'message' => $responseData['message'] ?? 'Unknown error']);
    }
} catch (Exception $e) {
    error_log("Exception caught: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>