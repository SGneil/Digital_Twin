<?php
    session_start();
    if (isset($_SESSION['account_id'])) {
        $account_id = $_SESSION['account_id']; 
        $username = $_SESSION['username'];
    } else {
        // 處理沒有設置 username 的情況，例如重定向到登錄頁面
        header('Location: ../../Lobby/front/login_page.php');
        exit();
    }
?>


<!DOCTYPE html>
<html>

<head>
  <title>檢查個人化資料</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/personalized_form.css">
</head>

<body>
    <div class="title">
        <p>[1] 上傳照片 ➡️ [2] 動作遷移影片結果 ➡️ <span class="text-color">[3]專員訪談-基本資料(6/6)</span> ➡️ [4] 測試數位分身 ➡️ [5] 公開數位分身</p>
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

    <!-- 登入卡片介面 -->
    <div class="card-bg">
        <div class="login-container">
                <div class="container mt-5">
                    <div id="modalsContainer"></div> <!-- 用來放新增的視窗 -->

                    <!-- 送出按鈕 -->
                    <button id="save-button" class="login-btn" type="button">送出</button>
                </div>
        </div>
    </div>


    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <input type="hidden" id="username" data-value="<?php echo $username; ?>">
    <input type="hidden" id="account_id" data-value="<?php echo $account_id; ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/letter.js"></script>

</body>

</html>