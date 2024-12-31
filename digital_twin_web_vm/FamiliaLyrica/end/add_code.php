<?php
    session_start();
    require('db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['code'])) {
            $code = $_POST['code'];
            $username = $_SESSION['username'];

            $sql = "SELECT `id` FROM `account` WHERE `username` = '$username'";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);
            $account_id = $row['id'];

            $sql = "SELECT `id` FROM `user_information` WHERE `binding_code` = '$code'";
            $result = mysqli_query($link, $sql);
            $rows=mysqli_num_rows($result);
            if ($rows) {
                $row = mysqli_fetch_assoc($result);
                $binding_code_id = $row['id'];

                $sql = "SELECT * FROM `descendants` WHERE `binding_code_id` = '$binding_code_id' AND `account_id` = '$account_id'";
                $result = mysqli_query($link, $sql);
                $rows=mysqli_num_rows($result);

                if (!$rows) {
                    $sql = "INSERT INTO `descendants` (`account_id`, `binding_code_id`) VALUES ('$account_id', '$binding_code_id')";
                    if ($link->query($sql) === TRUE) {
                        echo "代碼成功輸入。";
                    }
                }
                else {
                    echo "代碼重複輸入。123";
                }
                
            }
            else {
                echo "代碼錯誤，查無此代碼，請重新確認。";
            }
            $link->close();
        }
    }
?>
