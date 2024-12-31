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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/personalized_form.css">
</head>

<body>
    <div class="title">
        <p>[1] 上傳照片 ➡️ [2] 動作遷移影片結果 ➡️ <span class="text-color">[3]專員訪談-基本資料(1/6)</span> ➡️ [4] 測試數位分身 ➡️ [5] 公開數位分身</p>
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
            <form action="../end/login.php" method="post" class="login-form">
                <h2>檢查個人化資料</h2>
                <div class="input-group">
                    <label for="name">您的大名:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="gender">性別:</label>
                    <input type="text" id="gender" name="gender" required>
                </div>
                <div class="input-group">
                    <label for="birthday">生日:</label>
                    <input type="text" id="birthday" name="birthday" required>
                </div>
                <div class="input-group">
                    <label for="blood">血型:</label>
                    <input type="text" id="blood" name="blood" required>
                </div>
                <div class="input-group">
                    <label for="phone">電話:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="input-group">
                    <label for="address">居住地址:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <button id="save-button" class="login-btn" type="button">送出</button>
            </form>
        </div>
    </div>


    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <input type="hidden" id="account_id" data-value="<?php echo $account_id; ?>">
    <input type="hidden" id="username" data-value="<?php echo $username; ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/personalized_form.js"></script>

</body>

</html>