<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $save_path = $_POST['save_path'];

        $name = date("YmdHis") . '.mp4';
        $video_target_dir = "../..$save_path/$name"; // 视频文件保存路径
        
        // 檢查路徑是否正確
        if (!is_dir(dirname($video_target_dir))) {
            die("Directory does not exist: " . dirname($video_target_dir));
        }
        
        if (isset($_FILES['video'])) {

            if (move_uploaded_file($_FILES['video']['tmp_name'], $video_target_dir)) {
                echo $video_target_dir;
            } else {
                echo $video_target_dir . "<br>";
                echo "Failed to move uploaded video.";
            }
        } else {
            echo "No video file received.";
        }
    } else {
        echo "Invalid request method.";
    }
?>
