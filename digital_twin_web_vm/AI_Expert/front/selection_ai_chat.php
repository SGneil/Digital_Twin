<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    $family_id = $_GET['family_id'];
?>


<!DOCTYPE html>
<html>

<head>
  <title>選擇服務</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/preview_selection.css">
</head>

<body>
    <div class="title">
        <p>利用 AI 技術生成長輩虛擬化身 - 建立跨越時空的親屬互動</p>
    </div>

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

    <div class="bg">

    <div class="step">
            <a href="./ai_chat.php?type=1" class="btn">
                基本資料
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <a href="./ai_chat.php?type=4" class="btn">
                人生大事記
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <a href="./ai_chat.php?type=3" class="btn">
                人生回顧
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <a href="./ai_chat.php?type=2" class="btn">
                夢想清單
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <a href="./ai_chat.php?type=5" class="btn">
                未完成的事
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <a href="./ai_chat.php?type=6" class="btn">
            留給後代的信
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div>
            <a class="btn-back btn-secondary mt-3 btn-pad" href="preview_selection.php">返回主頁</a>
        </div>

    </div>
    <script src="preview_selection.js"></script>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="./js/home.js"></script>

</body>

</html>