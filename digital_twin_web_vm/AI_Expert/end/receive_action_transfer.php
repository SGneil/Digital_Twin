<?php
    require("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['user_name'];

        $sql = "SELECT * FROM `picture_information` WHERE `name` ='$name'";
        $result = $link->query($sql);
        $row = mysqli_fetch_assoc($result);

        $video_path = $row['video_path'];
        $video_target_dir = "../.." . $video_path; // 视频文件保存路径

        echo $video_target_dir;

        if (isset($_FILES['video'])) {
            $video_target_dir = move_uploaded_file($_FILES['video']['tmp_name'], $video_target_dir);
            // echo($image_target_file);
            // echo($video_target_dir);

            // 更改資料庫紀錄
            $update_sql = "UPDATE `picture_information` SET `video_state` = 'finish' WHERE `name` = '$name'";
            $result = $link->query($update_sql);
        }
        
        $link->close();
    }
    else {
        echo("你進來的方式不對~");
    }
?>