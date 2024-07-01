<?php
    session_start();

    // 检查文件类型和大小
    if (($_FILES["family_image"]["type"] == "image/png" || $_FILES["family_image"]["type"] == "image/jpg" || $_FILES["family_image"]["type"] == "image/jpeg") && $_FILES["family_image"]["size"] < 2 * 1024 * 1024) {

        // 检查上传过程中是否有错误
        if ($_FILES["family_image"]["error"] > 0) {
            echo("Return Code: " . $_FILES["family_image"]["error"] . "<br />");
        } else {
            // 文件上传成功
            $path_dir = '../front/family/' . $_SESSION['username'] . '/';
            $file = $_SESSION['username'] . date("YmdHis");
            $filename = $file . '.jpg';
            $filevideo = $file . '.mp4';
            $videopath = $_SESSION['username'] . '/' . $filevideo;
            $relative_picture_path = $_SESSION['username'] . '/' . $filename;
            
            // 将文件移动到指定目录
            move_uploaded_file($_FILES["family_image"]["tmp_name"], $path_dir . $filename);

            // 连接数据库
            require("db.php");

            // 获取表单数据
            $relative_name = $_POST['relative_name'];
            $relative_relation = $_POST['relative_relation'];
            $username = $_SESSION['username'];

            // 查询用户ID
            $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);
            $account_id = $row['id'];
            
            // 插入数据到数据库
            $sql = "INSERT INTO `relative_information` (`relative_name`, `relative_relation`, `relative_picture_path`, `picture_name`, `account_id`, `relative_video_name`, `relative_video_path`) VALUES ('$relative_name', '$relative_relation', '$relative_picture_path', '$filename', '$account_id', '$filevideo', '$videopath')";

            if ($link->query($sql) === TRUE) {
                $request_id = $account_id;

                // 返回请求ID给前端
                echo json_encode(['request_id' => $request_id]);
                $link->close();
    
                if (should_stop()) {
                    $filename = 'stop.txt'; // 文件名
                    $new_content = '1'; // 新的文件内容
                    // 将新内容写入文件
                    file_put_contents($filename, $new_content);

                    // 初始化 cURL
                    $ch = curl_init();

                    // 设置 cURL 选项
                    curl_setopt($ch, CURLOPT_URL, 'http://localhost/Digital_Twin/end/queue_move_body.php');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1); // 不等待响应

                    // 执行 cURL 请求
                    curl_exec($ch);

                }

            } else {
                echo "Error: " . $sql . "<br>" . $link->error;
            }
        }
    }

    function should_stop() {
        $stop_file = 'stop.txt';
        if (file_exists($stop_file)) {
            $content = trim(file_get_contents($stop_file));
            return $content === '0'; // 如果 stop.txt 内容为 '0'，返回 true 表示应该停止
        }
        return false; // 默认情况下继续执行
    }
?>
