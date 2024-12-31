<?php
require("port.php");
$flask_api_url = "http://{$python_api_vm_port}:5002/generate_prompt"; // 替換為實際的 URL

// 從 POST 請求中獲取 account_id
$account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
$information = isset($_POST['data']) ? $_POST['data'] : null;

if ($account_id === null) {
    die(json_encode(['error' => '未提供 account_id']));
}

$sql = "SELECT id 
        FROM user_information 
        WHERE account_id = ?";

require('db.php');
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result); 
$user_information_id = $row ? $row['id'] : null;

mysqli_stmt_close($stmt);
mysqli_close($link);

$data = array(
    'account_id' => $account_id,
    'user_information_id' => $user_information_id,
    'information' => $information,
);

$ch = curl_init($flask_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('data' => $data)));

// 執行 cURL 並獲取 API 回應
curl_exec($ch);
curl_close($ch);
?>
