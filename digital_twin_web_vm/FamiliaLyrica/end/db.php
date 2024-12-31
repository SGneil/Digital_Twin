<?php
        $host = 'localhost';
        $dbuser = 'root';
        $dbpw = '123qwe';
        $dbname = 'digital_twin';
        
        $link = mysqli_connect($host, $dbuser, $dbpw, $dbname);

        // if ($link) {
        //     mysqli_query($link,"SET NAMES utf8");
        //     echo '資料庫連線成功';
        // } else {
        //     echo '無法連線資料庫 :<br/>' .mysqli_connect_error();
        // }
?>