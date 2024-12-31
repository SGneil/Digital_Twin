<?php
    session_start();

    // 确保 GD 库已启用
    if (!extension_loaded('gd')) {
        die('GD library is not available.');
    }

    // 检查文件类型和大小
    if (
        ($_FILES["family_image"]["type"] == "image/png" ||
        $_FILES["family_image"]["type"] == "image/jpg" ||
        $_FILES["family_image"]["type"] == "image/jpeg") &&
        $_FILES["family_image"]["size"] < 2 * 1024 * 1024
    ) {
        // 检查上传过程中是否有错误
        if ($_FILES["family_image"]["error"] > 0) {
            echo "Return Code: " . $_FILES["family_image"]["error"] . "<br />";
        } else {
            // 文件上传成功
            $username = $_SESSION['username'];
            $path_dir = "../../family/{$username}/input/image/";
            $name = $username . date("YmdHis");
            $picture_name = $name . '.jpg';
            $video_name = $name . '.mp4';
            $picture_path = "/family/{$username}/input/image/{$picture_name}";
            $video_path = "/family/{$username}/input/video/{$video_name}";


            // 创建临时文件的路径
            $temp_file = $_FILES["family_image"]["tmp_name"];

            // 打开上传的图片
            switch ($_FILES["family_image"]["type"]) {
                case "image/jpeg":
                    $src_img = imagecreatefromjpeg($temp_file);
                    break;
                case "image/png":
                    $src_img = imagecreatefrompng($temp_file);
                    break;
                case "image/jpg":
                    $src_img = imagecreatefromjpeg($temp_file);
                    break;
                default:
                    die('Unsupported file type.');
            }

            // 获取原图宽高
            $src_width = imagesx($src_img);
            $src_height = imagesy($src_img);

            // 设定最大宽高
            $max_size = 1024;

            // 计算新图宽高
            if ($src_width > $max_size || $src_height > $max_size) {
                $ratio = $src_width / $src_height;
                if ($ratio > 1) {
                    $new_width = $max_size;
                    $new_height = $max_size / $ratio;
                } else {
                    $new_height = $max_size;
                    $new_width = $max_size * $ratio;
                }

                // 创建新的空白图像
                $dst_img = imagecreatetruecolor($max_size, $max_size);
                $white = imagecolorallocate($dst_img, 255, 255, 255); // 背景色为白色
                imagefill($dst_img, 0, 0, $white);

                // 将原图缩放并填充到目标图像中
                imagecopyresampled($dst_img, $src_img, ($max_size - $new_width) / 2, ($max_size - $new_height) / 2, 0, 0, $new_width, $new_height, $src_width, $src_height);

                // 保存缩放后的图像
                imagejpeg($dst_img, $path_dir . $picture_name);
            } else {
                // 如果不需要缩放，直接保存
                move_uploaded_file($temp_file, $path_dir . $picture_name);
            }

            // 释放内存
            imagedestroy($src_img);
            if (isset($dst_img)) {
                imagedestroy($dst_img);
            }

            // 连接数据库
            require("db.php");
            require("port.php");

            // 查询用户ID
            $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);
            $account_id = $row['id'];

            // // 插入数据到数据库
            // $sql = "INSERT INTO `picture_information` (`account_id`, `name`, `picture_name`, `picture_path`, `video_name`, `video_path`) VALUES ('$account_id', '$name', '$picture_name', '$picture_path', '$video_name', '$video_path')";

            // 先检查该 account_id 是否已经存在
            $sql_check = "SELECT * FROM `picture_information` WHERE `account_id` = '$account_id'";
            $result_check = mysqli_query($conn, $sql_check);

            if (mysqli_num_rows($result_check) > 0) {
                // 如果已存在，执行 UPDATE 操作
                $sql = "UPDATE `picture_information` SET `name` = '$name', `picture_name` = '$picture_name', `picture_path` = '$picture_path', `video_name` = '$video_name', `video_path` = '$video_path' WHERE `account_id` = '$account_id'";
            } else {
                // 如果不存在，执行 INSERT 操作
                $sql = "INSERT INTO `picture_information` (`account_id`, `name`, `picture_name`, `picture_path`, `video_name`, `video_path`) VALUES ('$account_id', '$name', '$picture_name', '$picture_path', '$video_name', '$video_path')";
            }

            if ($link->query($sql) === TRUE) {
                $request_id = $account_id;

                // 返回请求ID给前端
                echo json_encode(['request_id' => $request_id]);
                $link->close();

                $post = array(
                    'picture_name' => $picture_name,
                );

                // 初始化 cURL
                $ch = curl_init();

                // 设置 cURL 选项
                curl_setopt($ch, CURLOPT_URL, "http://{$php_web_vm_port}/digital_twin/AI_Expert/end/sent_action_transfer.php");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30); // 不等待响应

                // 执行 cURL 请求
                curl_exec($ch);

            } else {
                echo "Error: " . $sql . "<br>" . $link->error;
            }
        }
    }
    else {
        echo "請上傳小於2M的圖片或正確格式的檔案";
    }
?>
