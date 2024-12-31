<?php
// 接收來自 JavaScript 的 POST 請求資料
$data = json_decode(file_get_contents('php://input'), true);
$messages = $data['messages'];

// 將 messages 傳送到 Flask API
require("port.php");
$flask_api_url = "http://{$python_api_vm_port}:5002/gpt_chat";

// 使用 cURL 發送 POST 請求到 Flask API
$ch = curl_init($flask_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('messages' => $messages)));

// 執行 cURL 並獲取 API 回應
$response = curl_exec($ch);
curl_close($ch);

// 將 Flask API 的回應返回給 JavaScript
header('Content-Type: application/json');
echo $response;
?>
