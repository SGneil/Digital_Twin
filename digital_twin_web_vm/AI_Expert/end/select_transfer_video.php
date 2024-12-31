<?php
    require("db.php");

    $family_id = $_POST['family_id'];
    $sql = "SELECT * FROM `picture_information` WHERE `id` ='$family_id'";
    //執行sql
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);

    $account_id = $row['account_id'];
    $picture_path = $row['picture_path'];
    $transfer_video = $row['video_path'];
    $talk_video_folder = convertPath($transfer_video);

    $sql = "SELECT * FROM `user_information` WHERE `account_id` ='$account_id'";
    //執行sql
    $result = mysqli_query($link, $sql);
    //返回一個數值
    $rows=mysqli_num_rows($result);

    // 將資料設定到 user_information
    if (!$rows) {
        $insert_sql = "INSERT INTO `user_information` (`account_id`, `picture_path`, `transfer_video`, `talk_video_folder`) VALUES ('$account_id', '$picture_path', '$transfer_video', '$talk_video_folder')";
        $result = mysqli_query($link, $insert_sql);
        // $link->query($insert_sql);
    }
    else {
        $update_sql = "UPDATE `user_information` SET `picture_path` = '$picture_path', `transfer_video` = '$transfer_video', `talk_video_folder` = '$talk_video_folder' WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $update_sql);
    }

    // 搜尋 user_information_id
    $sql = "SELECT * FROM `user_information` WHERE `account_id` ='$account_id'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_information_id = $row['id'];

    // 設定 family_chat_prompt
    $update_sql = "UPDATE `family_chat_prompt` SET `user_information_id` = '$user_information_id' WHERE `account_id` = '$account_id'";
    $result = mysqli_query($link, $update_sql);

    $link->close();

    // 設定對嘴型影片資料夾
    function convertPath($inputPath) {
        // 確保路徑格式統一（防止反斜槓等問題）
        $inputPath = str_replace("\\", "/", $inputPath);
    
        // 將 input 替換為 output 並修改路徑
        $outputPath = str_replace("/input/video/", "/output/results/", $inputPath);
    
        $directoryPath = dirname($outputPath);
    
        return $directoryPath;
    }
?>