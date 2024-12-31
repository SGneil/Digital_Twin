<?php
    require('db.php');

    $family_id = $_POST['family_id'];

    $sql = "SELECT * FROM `picture_information` WHERE `id` ='$family_id'";
    // //執行sql
    $result = mysqli_query($link, $sql);
    // //返回一個數值
    $row = mysqli_fetch_assoc($result);
    $imagePath = '../..' . $row['picture_path'];
    $videoPath = '../..' . $row['video_path'];
    // echo $family_id;

    $rows=mysqli_num_rows($result);
    if ($rows) {
        $delete_sql = "DELETE FROM `picture_information` WHERE `id` = '$family_id'";
        $link->query($delete_sql);
    }
    $link->close();

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    if (file_exists($videoPath)) {
        unlink($videoPath);
    }
?>