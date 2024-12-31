<?php
    session_start();
    require("function.php");

    $check_step5 = check_step5($_SESSION['username']);
    
    echo $check_step5;
?>