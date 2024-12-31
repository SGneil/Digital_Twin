<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    require('../end/function.php');
    $user = load_user($_SESSION['username']);

    $family = load_fmaily_chat($_SESSION['username']);

    if ($family['transfer_video'] == 'doing') {
        $check = 'transfer-video-doing';
    }
    else {
        $check = 'yes';
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>上傳照片</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/open_family.css">
</head>

<body>
    <div class="title">
        <p>[1] 與AI專員聊天 ➡️ [2]檢查個人化表單內容 ➡️ [3] 讓照片動起來 ➡️ [4] 測試數位分身 ➡️ <span class="text-color">[5] 公開數位分身</span></p>
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
            <?php if ($user['binding_code'] == ""): ?>
                <a id="generateCodeBtn" class="btn">
                    生成代碼
                    <span></span><span></span><span></span><span></span>
                </a>

                <a href="../../AI_Expert/front/preview_selection.php" class="btn">
                    回上一頁
                    <span></span><span></span><span></span><span></span>
                </a>

                <div class="code-p">
                    <p>生成的代碼 : <span id="generatedCode"></span></p>
                </div>
                <a id="openCodeBtn" style="display: none;" class="btn">
                    開啟
                    <span></span><span></span><span></span><span></span>
                </a>
                <a id="closeCodeBtn" style="display: none;" class="btn">
                    關閉
                    <span></span><span></span><span></span><span></span>
                </a>
            <?php else: ?>
                <a id="generateCodeBtn" class="btn">
                    生成代碼
                    <span></span><span></span><span></span><span></span>
                </a>

                <a href="../../AI_Expert/front/preview_selection.php" class="btn">
                    回上一頁
                    <span></span><span></span><span></span><span></span>
                </a>

                <div class="code-p">
                    <p>生成的代碼 : <span id="generatedCode"><?php echo($user['binding_code']);?></span></p>
                </div>
                <a id="openCodeBtn" class="btn">
                    開啟
                    <span></span><span></span><span></span><span></span>
                </a>
                <a id="closeCodeBtn" class="btn">
                    關閉
                    <span></span><span></span><span></span><span></span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div id="cloneModal" class="modal">
        <div class="modal-content">
            <h2>請先讓照片動起來...</h2>
            <a href="../../AI_Expert/front/check_action_transfer.php" class="btn">
                前往讓照片動起來
                <span></span><span></span><span></span><span></span>
            </a>
        </div>
    </div>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/open_family.js"></script>

    <script>
        // PHP 將 check 的值傳遞給 JS
        const check = '<?php echo $check; ?>';

        // 如果 check 等於 'doing'，顯示彈框
        if (check === 'transfer-video-doing') {
            const modal = document.getElementById('cloneModal');
            modal.style.display = 'block';
        }
    </script>
</body>

</html>
