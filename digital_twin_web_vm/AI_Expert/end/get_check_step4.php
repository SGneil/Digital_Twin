<?php
    session_start();
    require("function.php");

    $check_step4 = check_step4($_SESSION['username']);
    
    echo $check_step4;
?>