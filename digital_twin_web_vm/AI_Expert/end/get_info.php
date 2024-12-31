<?php
header('Content-Type: application/json');
session_start();
require('db.php');
$account_id = $_POST['account_id'];

$sql = "SELECT personal_info, life_milestones, life_review, bucket_list, unfinished_business, letter 
        FROM user_provided_information 
        WHERE account_id = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => '找不到資料']);
}

mysqli_stmt_close($stmt);
mysqli_close($link);

?>
