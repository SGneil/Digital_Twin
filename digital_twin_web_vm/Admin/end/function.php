<?php

    function select_account() {
        require 'db.php';
        $sql = "SELECT * FROM `account`";
        $result = mysqli_query($link, $sql);

        $accounts = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['binding_code'] == '') {
                $binding_code = '未公開';
            }
            else {
                $binding_code = $row['binding_code'];
            }

            if ($row['identity'] == 'user') {
                $identity = '使用者';
            }
            else {
                $identity = '管理員';
            }

            $accounts[] = [
                'account_id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email'],
                'binding_code' => $binding_code,
                'identity' => $identity
            ];
        }
        $link->close();
        return $accounts; // 返回填充好的产品数组
    }

    function select_server_state($server_name) {
        require 'db.php';
        $sql = "SELECT * FROM `server_state` WHERE `server_name` = '$server_name'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        $server_state = [
            'server_id' => $row['id'],
            'server_name' => $row['server_name'],
            'state' => $row['state']
        ];
        $link->close();
        return $server_state; // 返回填充好的产品数组
    }

    function update_server_state($server_name, $state) {
        require 'db.php';
        $sql = "UPDATE `server_state` SET `state` = '$state' WHERE `server_name` = '$server_name'";
        mysqli_query($link, $sql);
        $link->close();
    }

    function load_user($account_id) {
        require "db.php";
        
        $sql = "SELECT * FROM `account` WHERE `id` = '$account_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $user = [
            'account_id' => $row['id'],
            'username' => $row['username'],
            'password' => $row['password'],
            'email' => $row['email'],
            'binding_code' => $row['binding_code'],
            'identity' => $row['identity'],
        ];

        return $user;
    }

    function load_user_chat($account_id) {
        require "db.php";
        
        $sql = "SELECT * FROM `chats` WHERE `account_id` = '$account_id' ORDER BY `topic_id` ASC";
        $result = mysqli_query($link, $sql);

        $user = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['topic_id'] == '1') {
                $topic = '基本資料';
            }
            else if ($row['topic_id'] == '4') {
                $topic = '人生大事記';
            }
            else if ($row['topic_id'] == '3') {
                $topic = '人生回顧';
            }
            else if ($row['topic_id'] == '2') {
                $topic = '夢想清單';
            }
            else if ($row['topic_id'] == '5') {
                $topic = '未完成的事';
            }
            else {
                $topic = '留給後代的信';
            }

            if ($row['role'] == 'gpt') {
                $role = 'AI 專員';
            }
            else {
                $role = '使用者';
            }
            
            $user[] = [
                'chat_id' => $row['id'],
                'username' => $row['username'],
                'topic' => $topic,
                'account_id' => $row['account_id'],
                'role' => $role,
                'content' => $row['content']
            ];
        }
        return $user;
    }

    function load_family_chat($account_id) {
        require "db.php";
        
        $user = [];

        $sql = "SELECT * FROM `test_chat_history` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            
            if ($row['role'] == 'gpt') {
                $role = 'AI 專員';
            }
            else {
                $role = '使用者';
            }

            $topic = '測試親人對話';
            
            $user[] = [
                'chat_id' => $row['id'],
                'topic' => $topic,
                'account_id' => $row['account_id'],
                'role' => $role,
                'text' => $row['text']
            ];
        }

        $sql = "SELECT * FROM `family_chat_history` WHERE `account_id` = '$account_id'";
        $result = mysqli_query($link, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            
            if ($row['role'] == 'gpt') {
                $role = 'AI 親人';
            }
            else {
                $role = '使用者';
            }

            $topic = '公開親人對話';
            
            $user[] = [
                'chat_id' => $row['id'],
                'topic' => $topic,
                'account_id' => $row['account_id'],
                'role' => $role,
                'text' => $row['text']
            ];
        }
        return $user;
    }
?>