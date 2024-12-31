<?php
header('Content-Type: application/json'); // 設定回傳資料格式為 JSON
    session_start();
    require('db.php');
    // 獲取POST數據
    $topic_id = $_POST['topic_id'];
    $account_id = $_POST['account_id'];
    $role = $_POST['role'];
    $content = $_POST['content'];

    // 使用預處理語句來防止SQL注入
    $sql = "INSERT INTO `chats` (`topic_id`, `account_id`, `role`, `content`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $topic_id, $account_id, $role, $content);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($link)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>