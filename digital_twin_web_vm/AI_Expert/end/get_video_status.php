<?php
    session_start();
    require("function.php");

    $state = load_video_state($_SESSION['username']);
    
    echo $state;
?>