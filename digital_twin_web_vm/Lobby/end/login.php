<?php

    session_start();    
    require_once 'db.php';

    // 獲取表單提交的用戶名和密碼
    $username = $_POST['username'];
    $password = $_POST['password'];

    //如果使用者名稱和密碼都不為空
    if ($username && $password) {
        //檢測資料庫是否有對應的username和password的sql
        $sql = "SELECT * FROM `account` WHERE `username` ='$username' and `password` = '$password'";
        //執行sql
        $result = mysqli_query($link, $sql);
        //返回一個數值
        $rows=mysqli_num_rows($result);
        //0 false 1 true
        if ($rows) {
            // echo('登入成功');
            if (isset($_SESSION['login_msg'])) {
                unset($_SESSION['login_msg']);
            }
            // 创建预处理语句
            $stmt = $link->prepare("SELECT * FROM `account` WHERE `username` = ? AND `password` = ?");
            $stmt->bind_param("ss", $username, $password); // 'ss'指定了参数的类型：'s' = string

            // 执行预处理语句
            $stmt->execute();

            // 获取结果集
            $result = $stmt->get_result();

            // 从结果集中提取信息
            if ($row = $result->fetch_assoc()) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['position'] = $row['position'];
                $_SESSION['username'] = $username;
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['account_id'] = $row['id'];
                $_SESSION['identity'] = $row['identity'];
                // echo($row['position']);
            }
            // 关闭语句
            // $stmt->close();
            if ($_SESSION['identity'] == 'user') {
                header('Location: ../front/select_service.php');
            }
            else {
                header('Location: ../../Admin/front/management_interface.php');
            }
        }
        else {
            // echo('登入失敗');
            $_SESSION['login_msg'] = '登入失敗';
            header('Location: ../front/login_page.php');
        }
    }
    
    exit();
?>