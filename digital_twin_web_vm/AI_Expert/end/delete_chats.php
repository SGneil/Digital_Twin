<?php
header('Content-Type: application/json'); // 設定回傳資料格式為 JSON
session_start();
require('db.php'); // 包含 MySQLi 資料庫連線

// 檢查 POST 請求中的參數
if (isset($_POST['topic_id'], $_POST['account_id'])) {
    $topic_id = $_POST['topic_id'];
    $account_id = $_POST['account_id'];

    // 準備 SQL 刪除語句
    $sql = "DELETE FROM chats 
            WHERE topic_id = ? AND account_id = ?";

    // 初始化 Prepared Statement
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
        // 綁定參數
        mysqli_stmt_bind_param($stmt, "ii", $topic_id, $account_id);

        // 執行刪除
        if (mysqli_stmt_execute($stmt)) {
            // 檢查受影響的行數
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo json_encode(['message' => '刪除成功']);
            } else {
                echo json_encode(['message' => '沒有符合的記錄']);
            }
        } else {
            echo json_encode(['error' => '刪除失敗']);
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
