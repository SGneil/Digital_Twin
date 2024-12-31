<?php
    session_start();
    require("function.php");

    if (isset($_POST['server_name']) && isset($_POST['state'])) {
        update_server_state($_POST['server_name'], $_POST['state']);
        echo 'Success';
    }
    else {
        echo 'Error';
    }
?>
