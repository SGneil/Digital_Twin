<?php
    session_start();
    require("function.php");

    $check_step4_unlock = check_step4_unlock($_SESSION['username']);
    
    echo $check_step4_unlock;
?>