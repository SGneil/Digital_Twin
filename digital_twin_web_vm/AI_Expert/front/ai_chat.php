<?php
    session_start();
    if (isset($_SESSION['account_id'])) {
        $account_id = $_SESSION['account_id']; 
        $username = $_SESSION['username'];
    } else {
        // 處理沒有設置 account_id 的情況，例如重定向到登錄頁面
        header('Location: ../../Lobby/front/login_page.php');
        exit();
    }
    $type = $_GET['type'];

    $title_text = '';
    if ($type == 1) {
        $title_text = '[1]專員訪談-基本資料(1/6) ';
    }
    else if ($type == 2) {
        $title_text = '[1]專員訪談-夢想清單(4/6)';
    }
    else if ($type == 3) {
        $title_text = '[1]專員訪談-人生回顧(3/6)';
    }
    else if ($type == 4) {
        $title_text = '[1]專員訪談-人生大事記(2/6)';
    }
    else if ($type == 5) {
        $title_text = '[1]專員訪談-未完成的事(5/6)';
    }
    else if ($type == 6) {
        $title_text = '[1]專員訪談-我的遺留物(6/6)';
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>與親人聊天</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" href="./css/main.css">

    <link rel="stylesheet" href="./css/ai_chat.css" />
    <!-- Google Fonts Links - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>

</head>

<body>
    <div class="title">
        <p><span class="text-color"><?php echo($title_text);?></span> ➡️ [2] 讓照片動起來 ➡️ [3] 檢查個人化表單內容 ➡️ [4] 測試數位分身 ➡️ [5] 公開數位分身</p>
    </div>
    <audio id="audioPlayer" controls style="display: none;"></audio>

    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../../Lobby/front/select_service.php">Virtual Household：虛擬家庭</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">三</span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../AI_Expert/front/preview_selection.php">AI 專員</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../FamiliaLyrica/front/set_information.php">親人對話</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Lobby/front/logout.php">登出</a>
                    </li>
                </ul>

                <div class="navbar-icons-bottom d-lg-none">
                    <a href="../../Lobby/front/select_service.php" class="navbar-icon-link">
                        <i class="fas fa-user"></i>
                    </a>
                </div>

            </div>

            <div class="navbar-icons d-none d-lg-flex">
                <a href="../../Lobby/front/select_service.php" class="navbar-icon-link">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- 主頁面，包含按鈕 -->
    <div id="main-page" class="page active">
    <div class="container chat-bg">
            <div class="container-left">
                <div class="image-display">
                    <img id="userImage" src="./img/boy.jpg" alt="Main Image">
                </div>
                <div class="thumbnails">
                    <img class="thumbnail" src="./img/boy.jpg" alt="Thumbnail 1">
                    <img class="thumbnail" src="./img/mouse.jpg" alt="Thumbnail 2">
                    <img class="thumbnail" src="./img/girl.jpg" alt="Thumbnail 3">
                </div>
                <div>
                    <a class="btn btn-secondary mt-3 btn-pad" href="preview_selection.php">返回主頁</a>
                    <button class="btn btn-secondary mt-3 btn-pad" onclick="showMainPage()">返回上一頁</button>
                </div>
                
            </div>

            <div class="container-right">
                <div class="wrapper">
                    <div class="header">
                        <span class="material-symbols-outlined"> menu </span>
                        <p>線上客服</p>
                        <span class="material-symbols-outlined"> filter_alt </span>
                    </div>
                    <div class="chat-page">
                        <div class="chats">                
                        </div>
                    </div>
                    <!-- <div class="chat-input"> -->
                        <!-- <button id="recordButton">按一下發話</button> -->
                        <!-- <script>
                            // 隱藏按鈕
                            document.getElementById('recordButton').style.display = 'none';
                        </script> -->

                        <!-- 開發臨時用 -->
                        <!-- <input type="text" placeholder="Type Message..." id="txt" />
                        <label for="txt" id="btn">Send</label> -->
                    <!-- </div> -->
                    <!-- 新增刪除按鈕 -->
                    <div class="centered-buttons">
                        <button id="recordButton" class="btn btn-primary mt-3">按一下發話</button>

                        <button id="deleteButton" class="btn btn-danger mt-3">刪除對話</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 傳php值，到js用 -->
    <div id="account_id" data-value="<?php echo $account_id; ?>"></div>
    <div id="username" data-value="<?php echo $username; ?>"></div>
    <div id="type" data-value="<?php echo $type; ?>"></div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/ai_chat.js"></script>

</body>

</html>
