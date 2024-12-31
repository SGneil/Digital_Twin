<?php
    header('Content-Type: application/json');
    session_start();
    require('db.php');

    function sendJsonResponse($data) {
        echo json_encode($data);
        exit;
    }

    function logError($message) {
        error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'error.log');
    }

    // 檢查 POST 請求中的參數
    if (!isset($_POST['topic_id']) || !isset($_POST['account_id'])) {
        sendJsonResponse(['error' => '無效的請求']);
    }

    $topic_id = $_POST['topic_id'];
    $account_id = $_POST['account_id'];

    // 準備 SQL 查詢語句
    $sql = "SELECT role, content FROM chats 
            WHERE topic_id = ? AND account_id = ?";

    // 初始化 Prepared Statement
    $stmt = mysqli_prepare($link, $sql);

    if (!$stmt) {
        sendJsonResponse(['error' => '無法準備 SQL 語句']);
    }

    // 綁定參數
    mysqli_stmt_bind_param($stmt, "ii", $topic_id, $account_id);

    // 執行查詢
    if (!mysqli_stmt_execute($stmt)) {
        sendJsonResponse(['error' => '執行查詢失敗']);
    }

    // 取得結果
    $result = mysqli_stmt_get_result($stmt);

    $messages = "";

    switch ($topic_id) {
        case 2:
            $messages .= "user: 只保留夢想清單\n";
            break;
        case 3:
            $messages = "user: 只保留人生回顧\n";
            break;
        case 4:
            $messages = "user: 只保留人生大事記\n";
            break;
        case 5:
            $messages = "user: 只保留未完成的事\n";
            break;
        case 6:
            $messages = "user: 留給後代的信\n";
            break;
    }
    // 如果有查詢結果，將每一筆結果按照指定格式儲存到字串中
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $messages .= $row['role'] . ': ' . $row['content'] . "\n";
        }
    }

    // 關閉 Statement
    mysqli_stmt_close($stmt);

    // 關閉資料庫連線
    mysqli_close($link);

    // 如果沒有消息，返回空數組
    if (empty($messages)) {
        sendJsonResponse([]);
    }

    require("port.php");
    $flask_api_url = "http://{$python_api_vm_port}:5002/conversation_summary";
    logError("嘗試連接到 Flask API: " . $flask_api_url);

    // 使用 cURL 發送 POST 請求到 Flask API
    $ch = curl_init($flask_api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('messages' => $messages)));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 設置超時時間為 30 秒

    // 執行 cURL 並獲取 API 回應
    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        logError("cURL 錯誤: " . $error);
        sendJsonResponse(['error' => 'Flask API 請求失敗: ' . $error]);
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    logError("HTTP 狀態碼: " . $httpCode);

    curl_close($ch);

    // 記錄 Flask API 的原始回應
    logError("Flask API 回應: " . $response);

    // 嘗試解碼 Flask API 的回應
    $decoded_response = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        logError("JSON 解析錯誤: " . json_last_error_msg());
        sendJsonResponse(['error' => 'Flask API 返回無效的 JSON']);
    }

    // 將 Flask API 的回應返回給 JavaScript
    echo $response;
?>