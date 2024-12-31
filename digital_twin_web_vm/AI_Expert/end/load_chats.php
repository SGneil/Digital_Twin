<?php
header('Content-Type: application/json'); // 設定回傳資料格式為 JSON
session_start();
require('db.php'); // 包含 MySQLi 資料庫連線

// 檢查 POST 請求中的參數
if (isset($_POST['topic_id'], $_POST['account_id'])) {
    $topic_id = $_POST['topic_id'];
    $account_id = $_POST['account_id'];

    // 準備 SQL 查詢語句
    $sql = "SELECT role, content FROM chats 
            WHERE topic_id = ? AND account_id = ?";

    // 初始化 Prepared Statement
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
        // 綁定參數
        mysqli_stmt_bind_param($stmt, "ii", $topic_id, $account_id);

        // 執行查詢
        mysqli_stmt_execute($stmt);

        // 取得結果
        $result = mysqli_stmt_get_result($stmt);

        $messages = array();

        // 如果有查詢結果，將每一筆結果儲存到陣列中
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $messages[] = $row;
            }
        }

        // 回傳查詢結果
        if (!empty($messages)) {
            echo json_encode($messages);
        } else {
            // 沒有符合的記錄，回傳特定訊息
            echo json_encode(['message' => '沒有符合的記錄']);
        }

        // 關閉 Statement
        mysqli_stmt_close($stmt);
    } else {
        // 無法準備 SQL 語句
        echo json_encode(['error' => '無法準備 SQL 語句']);
    }
} else {
    // 沒有收到正確的 POST 參數
    echo json_encode(['error' => '無效的請求']);
}

// 關閉資料庫連線
mysqli_close($link);
?>
