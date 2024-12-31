<?php
    session_start();
    require("function.php");

    if (isset($_GET['server_name'])) {
        $server_state = select_server_state($_GET['server_name']);
        echo $server_state['state'];
    }
    else {
        echo 'Error';
    }
?>