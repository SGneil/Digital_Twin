<?php
    // 傳送照片到API伺服器生成動作遷移影片

    function process_videos() {
        require('db.php');
        $picture_name = $_POST['picture_name'];
        // $filename = 'yimin20240724133539.jpg';
        $sql = "SELECT * FROM `picture_information` WHERE `picture_name` ='$picture_name'";
        $result = $link->query($sql);
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $picture_name = $row['picture_name'];
        $picture_name = pathinfo($picture_name);
        $picture_name = $picture_name['filename'];
        $picture_path = $row['picture_path'];
        $driving = "../../source/one.mp4";

        sent_action_transfer_image($id, $picture_name, $picture_path, $driving);
        $link->close();
    }

    // 傳送 api
    function sent_action_transfer_image($family_id, $filename, $image, $driving) {
        require("port.php");

        $target_url = "http://{$php_api_vm_port}/digital_twin/WEB_Server/LivePortrait/action_transfer.php"; // 服务器B的接收文件URL
        // $target_url = 'http://35.206.227.52/web/digital_twin/LivePortrait/action_transfer.php'; // 服务器B的接收文件URL

        $image_path = "../..{$image}"; // 图片文件路径
        $video_path = $driving; // 视频文件路径
    
        // 检查文件路径
        if (!file_exists($image_path)) {
            echo "Image file does not exist: $image_path\n";
            return;
        }
    
        if (!file_exists($video_path)) {
            echo "Video file does not exist: $video_path\n";
            return;
        }
    
        $image_file = curl_file_create($image_path);
        $video_file = curl_file_create($video_path);
    
        $post = array(
            'family_id' => $family_id,
            'user_name' => $filename,
            'image' => $image_file,
            'video' => $video_file
        );
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        if ($response === false) {
            echo 'Error: ' . curl_error($ch) . "\n";
        } else {
            echo 'Response: ' . $response . "\n";
        }
    
        curl_close($ch);
       
    }

    process_videos();
?>
