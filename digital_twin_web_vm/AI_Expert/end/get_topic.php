<?php
header('Content-Type: application/json'); // 設定回傳資料格式為 JSON
session_start();
require('db.php');

// 獲取POST數據
$topic_id = $_POST['topic_id'];

// 使用預處理語句來防止SQL注入
$sql = "SELECT `prompt` FROM `topics` WHERE `id` = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $topic_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $prompt);
mysqli_stmt_fetch($stmt);

if ($prompt) {
    echo json_encode(["status" => "success", "prompt" => $prompt]);
} else {
    echo json_encode(["status" => "error", "message" => "Topic not found"]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>