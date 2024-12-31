<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $save_path = $_POST['save_path'];

        $name = date("YmdHis") . '.wav';
        $audio_target_dir = "../..$save_path/$name"; // 视频文件保存路径
        
        // 檢查路徑是否正確
        if (!is_dir(dirname($audio_target_dir))) {
            die("Directory does not exist: " . dirname($audio_target_dir));
        }
        
        if (isset($_FILES['audio'])) {

            if (move_uploaded_file($_FILES['audio']['tmp_name'], $audio_target_dir)) {
                echo $audio_target_dir;
            } else {
                echo $audio_target_dir . "<br>";
                echo "Failed to move uploaded audio.";
            }
        } else {
            echo "No audio file received.";
        }
    } else {
        echo "Invalid request method.";
    }
?>
