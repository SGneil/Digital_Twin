<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ./login_page.php');
    }
    require("../end/function.php");
    $user = load_user($_SESSION['username']);

    if ($_SESSION['identity'] != 'user') {
        header('Location: ../../Admin/front/management_interface.php');
    }
?>


<!DOCTYPE html>
<html>

<head>
  <title>選擇服務</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/select_service.css">
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

    <!-- 使用者卡片介面 -->
    <div class="card-bg">
        <!-- <div class="user-profile-container">
            <div class="user-info">
                <h2>歡迎您好~</h2>
                <div class="user-profile-card">
                    <div class="user-avatar">
                        <img src="./img/user2.jpg" alt="大頭貼">
                    </div>
                    <div class="user-details">
                        <p>名稱: <?php echo $user['username']; ?></p>
                        <p>Email: <?php echo $user['email']; ?></p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="user-profile-container">
            <div class="select-card">
                <h4>AI專員記錄您的個性、生平與夢想</h4>
                <p>與我們的AI專員聊天，讓您的記憶得以保存，文化得以傳承。AI專員可以記錄您的個性、生平和夢想，並將這些寶貴的資訊永久保存。無論是分享您的生活故事，還是記錄下您的未來願景，我們的AI專員都能成為您信賴的記錄者，讓您的經歷和智慧在未來世代中傳承下去。</p>
                <a href="../../AI_Expert/front/preview_selection.php" class="btn">
                    前往與 AI 專員
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
        </div>

        <div class="user-profile-container">
            <div class="select-card">
                <h4>與親人對話的紀錄保存</h4>
                <p>透過AI專員，保存您與親人之間的對話紀錄，讓每一段珍貴的交流都能永久留存。無論是與父母的溫馨對話，還是與子女的深情交流，AI專員都能將這些美好的瞬間記錄下來。這不僅是對當下的珍視，更是對未來的傳承，讓愛與回憶在時光中不斷延續。</p>
                <a href="../../FamiliaLyrica/front/set_information.php" class="btn">
                    前往與親人對話
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
        </div>
    </div>

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