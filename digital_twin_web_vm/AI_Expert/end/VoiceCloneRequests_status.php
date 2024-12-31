<?php
session_start();
require("db.php");

// 只接受 POST 參數
$account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
$topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : 'check';
$status = isset($_POST['status']) ? $_POST['status'] : null;

// 檢查參數是否存在
if ($account_id === null || $topic_id === null) {
    echo 'false';
    exit;
}

if ($action === 'check') {
    $sql = "SELECT request_status FROM VoiceCloneRequests 
            WHERE account_id = ? AND topic_id = ?";
    
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $account_id, $topic_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['request_status'] ? 'true' : 'false';
    } else {
        // 如果找不到記錄，插入新記錄
        $insert_sql = "INSERT INTO VoiceCloneRequests (account_id, topic_id, request_status) 
                      VALUES (?, ?, 0)";
        $insert_stmt = mysqli_prepare($link, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ii", $account_id, $topic_id);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            echo 'false';
        } else {
            echo 'false';
        }
        mysqli_stmt_close($insert_stmt);
    }
} 
elseif ($action === 'update' && $status !== null) {
    $sql = "UPDATE VoiceCloneRequests 
            SET request_status = ? 
            WHERE account_id = ? AND topic_id = ?";
    
    $stmt = mysqli_prepare($link, $sql);
    $status_bool = ($status === 'true') ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "iii", $status_bool, $account_id, $topic_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo 'success';
    } else {
        echo 'error';
    }
}

// 關閉連接
mysqli_stmt_close($stmt);
mysqli_close($link);
?>