<?php
    require('db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['descendants_id'])) {
            $descendants_id = $_POST['descendants_id'];

            $delete_sql = "DELETE FROM `descendants` WHERE `id` = '$descendants_id'";
            if ($link->query($delete_sql) === TRUE) {
                echo ("刪除成功");
            }
            else {
                echo ("刪除失敗");
            }
            $link->close();
        }
    }
?>
