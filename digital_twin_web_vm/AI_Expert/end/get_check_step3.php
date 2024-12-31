<?php
    session_start();
    require("function.php");

    $check_step3 = check_step3($_SESSION['username']);
    
    echo $check_step3;
?>