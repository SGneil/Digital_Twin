<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user']) && isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        $user = $_POST['user'];
        $uploadDir = '../../family/' . $user . '/input/audio/';

        // 確保目錄存在
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadFile = $uploadDir . basename($_FILES['audio']['name']);
        if (move_uploaded_file($_FILES['audio']['tmp_name'], $uploadFile)) {
            // 只移除一次 '../'
            $cleanPath = preg_replace('/\.\.\//', '', $uploadFile, 1);
            
            // 呼叫STT服務
            $sttResult = callSTTService($cleanPath);
            echo json_encode(['message' => 'Audio file successfully saved', 'path' => $cleanPath, 'stt_result' => $sttResult]);
        } else {
            echo json_encode(['error' => 'Error saving audio file']);
        }
    } else {
        echo json_encode(['error' => 'No audio file uploaded or there was an error with the upload']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function callSTTService($audioFilePath) {
    $url = 'http://localhost:5002/stt';  // 請確保這是正確的STT服務地址
    $postData = [
        'file_path' => $audioFilePath
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
