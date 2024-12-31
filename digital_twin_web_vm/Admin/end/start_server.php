<?php
header('Content-Type: application/json');

$server = $_GET['server'] ?? '';
if(empty($server)) {
    // echo json_encode(['status' => 'error', 'message' => 'Server name required']);
    exit;
}

// 將伺服器名稱轉換為程式ID
$server_to_id = [
    'ChatGPT' => 'chatgpt',
    'Easy-Wav2Lip' => 'wav2lip',
    'GPT-SoVITS-Inference_api' => 'GPT-SoVITS-Inference_api',
    'GPT-SoVITS-Inference_pure-api' => 'GPT-SoVITS-Inference_pure-api',
    'GPT-SoVits-trained' => 'GPT-SoVITS-Trained',
    'LivePortrait' => 'liveportrait',
    'Watermark' => 'watermark'
];

$program_id = $server_to_id[$server] ?? '';
if(empty($program_id)) {
    // echo json_encode(['status' => 'error', 'message' => 'Invalid server name']);
    exit;
}

// 發送請求到Python伺服器
$response = file_get_contents("http://163.17.11.41:5010/start/$program_id");

echo $response; 