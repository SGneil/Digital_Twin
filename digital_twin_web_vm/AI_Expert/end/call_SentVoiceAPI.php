<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$url = 'http://localhost:5000/compress_and_send';

require("port.php");
$API_url = "http://{$python_api_vm_port}:5011/run_training"; // 替換為實際的 URL

// 從 POST 請求中獲取 username
$username = $_POST['username']; 
$account_id = $_POST['account_id'];
$topic_id = $_POST['topic_id'];
$data = array(
    'username' => $username,
    'account_id' => $account_id,
    'topic_id' => $topic_id,
    'API_url' => $API_url
);


$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "發生錯誤：無法連接到 Python 服務器\n";
    echo "錯誤詳情：" . error_get_last()['message'];
} else {
    // 處理成功響應
    $response = json_decode($result, true);
    if (isset($response['error'])) {
        echo "錯誤：" . $response['error'];
    } else {
        echo "成功：" . $response['message'];
    }
}
?>