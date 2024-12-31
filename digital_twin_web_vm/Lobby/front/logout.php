<?php

    // 啟動會話
    session_start();

    // 銷毀會話
    session_destroy();

    // 清除會話 Cookie
    setcookie(session_name(), '', time() - 3600, '/');

    // 重定向到登入頁面或其他頁面
    header("Location: ../front/login_page.php");
    exit();

?>