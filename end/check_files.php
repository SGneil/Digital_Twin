<?php
    session_start();

    // 指定要检查的文件夹路径
    $folderPath = '../front/family/' . $_SESSION['username'];

    // 获取文件夹中的文件列表
    $files = scandir($folderPath);

    // 过滤掉 '.' 和 '..' 这两个特殊目录
    $files = array_diff($files, array('.', '..'));

    // 初始化空数组来存储符合条件的文件名
    $matchedFiles = [];

    // 遍历文件列表，查找同名的 .jpg 和 .mp4 文件
    foreach ($files as $file) {
        // 提取文件名和后缀
        $info = pathinfo($file);
        $basename = $info['filename'];
        $extension = isset($info['extension']) ? $info['extension'] : '';

        // 如果是 .jpg 文件，并且对应的 .mp4 文件也存在，则加入到结果数组中
        if ($extension === 'jpg' && in_array("$basename.mp4", $files)) {
            $matchedFiles[] = $basename;
        }
    }

    require('db.php');
    foreach ($matchedFiles as $file) {
        $relative_video_path = $_SESSION['username'] . '/' . $file . '.mp4';
        $video_state = 'YES';
        $image = $file . '.jpg';
        $update_sql = "UPDATE `relative_information` SET `relative_video_path` = '$relative_video_path', `video_state` = '$video_state' WHERE `picture_name` = '$image'";

        // 执行 SQL 语句
        $result = $link->query($update_sql);

        if ($result) {
            echo "Record updated successfully for file: $file<br>";
        } else {
            echo "Error updating record for file: $file - " . $link->error . "<br>";
        }
    }

    $link->close();


    // 返回符合条件的文件名数组
    echo json_encode(['data' => $matchedFiles]);
?>
