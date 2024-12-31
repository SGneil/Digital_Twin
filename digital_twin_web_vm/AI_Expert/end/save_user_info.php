<?php
header('Content-Type: application/json');
session_start();
require('db.php');

// 在文件開頭添加
error_log("開始處理保存用戶信息請求");

switch ($_POST['topic_id']) {
    case 1:
        // 處理個人信息
        $required_fields = ['account_id', 'name', 'gender', 'birthday', 'blood', 'phone', 'address'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺少必要字段：$field"]);
                exit;
            }
        }

        $personal_info = '{"name": "' . addslashes($_POST['name']) . '","gender": "' . addslashes($_POST['gender']) . '","birthday": "' . addslashes($_POST['birthday']) . '","blood": "' . addslashes($_POST['blood']) . '","phone": "' . addslashes($_POST['phone']) . '","address": "' . addslashes($_POST['address']) . '"}';

        $sql = "INSERT INTO user_provided_information (account_id, personal_info) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                personal_info = VALUES(personal_info)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備執行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $personal_info);
    break;

    case 2:
        // 處理 bucket_list
        $required_fields = ['account_id', 'bucket_list'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺少必要字段：$field"]);
                exit;
            }
        }

        $bucket_list = $_POST['bucket_list'];

        $sql = "INSERT INTO user_provided_information (account_id, bucket_list) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                bucket_list = VALUES(bucket_list)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備執行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $bucket_list);
    break;

    case 3:
        // 處理 life_review
        $required_fields = ['account_id', 'life_review'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺��必要字段：$field"]);
                exit;
            }
        }

        $life_review = $_POST['life_review'];

        $sql = "INSERT INTO user_provided_information (account_id, life_review) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                life_review = VALUES(life_review)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備執行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $life_review);
    break;

    case 4:
        // 處理 life_milestones
        $required_fields = ['account_id', 'life_milestones'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺少必要字段：$field"]);
                exit;
            }
        }

        $life_milestones = $_POST['life_milestones'];

        $sql = "INSERT INTO user_provided_information (account_id, life_milestones) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                life_milestones = VALUES(life_milestones)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備��行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $life_milestones);
    break;

    case 5:
        // 處理 unfinished_business
        $required_fields = ['account_id', 'unfinished_business'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺少必要字段：$field"]);
                exit;
            }
        }

        $unfinished_business = $_POST['unfinished_business'];

        $sql = "INSERT INTO user_provided_information (account_id, unfinished_business) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                unfinished_business = VALUES(unfinished_business)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備執行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $unfinished_business);
    break;

    case 6:
        // 處理 letter
        $required_fields = ['account_id', 'letter'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "缺少必要字段：$field"]);
                exit;
            }
        }

        $letter = $_POST['letter'];

        $sql = "INSERT INTO user_provided_information (account_id, letter) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE 
                letter = VALUES(letter)";
        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            exit;
        }

        error_log("準備執行的 SQL 語句: " . $sql);
        mysqli_stmt_bind_param($stmt, "is", $_POST['account_id'], $letter);
    break;
}

// 修改執行語句的部分
$retryCount = 0;
$maxRetries = 1; // 最多重試一次

do {
    if (mysqli_stmt_execute($stmt)) {
        error_log("SQL 語句執行成功");
        echo json_encode(['success' => true, 'message' => '資料已成功保存']);
        break;
    } else {
        error_log("SQL 語句執行失敗 (嘗試 " . ($retryCount + 1) . "): " . mysqli_error($link));
        
        if ($retryCount < $maxRetries) {
            error_log("準備進行重試...");
            sleep(1); // 等待1秒後重試
        } else {
            error_log("已達到最大重試次數，放棄執行");
            echo json_encode(['success' => false, 'message' => '保存資料時發生錯誤: ' . mysqli_error($link)]);
            break;
        }
    }
    $retryCount++;
} while ($retryCount <= $maxRetries);

mysqli_stmt_close($stmt);
mysqli_close($link);
