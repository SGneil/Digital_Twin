<?php
    function generateRandomCode($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        while (true) {
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            require('db.php');
            $sql = "SELECT * FROM `account` WHERE `binding_code` ='$randomString'";
            $result = mysqli_query($link, $sql);
            $rows=mysqli_num_rows($result);
            if (!$rows) {
                upload_Random_code($randomString);
                break;
            }
            else {
                $randomString = '';
            }
        }
        
        return $randomString;
    }

    function upload_Random_code($code) {
        require('db.php');
        session_start();
        $username = $_SESSION['username'];
        $update_sql = "UPDATE `account` SET `binding_code` = '$code' WHERE `username` = '$username'";
        $link->query($update_sql);
        $link->close();
    }

    echo generateRandomCode();
?>
