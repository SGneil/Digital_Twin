<?php
    require('../end/function.php');
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    $check_step3 = check_step3($_SESSION['username']);
    $check_step4 = check_step4($_SESSION['username']);
    $check_step4_unlock = check_step4_unlock($_SESSION['username']);
    $check_step5 = check_step5($_SESSION['username']);
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
        <h1>歡迎來到數位分身體驗平台</h1>
        <p>讓我們引領您進入虛擬世界，輕鬆創建您的數位分身！</p>
        
        <div class="step">
            <h2>Step 1: 與AI專員聊天</h2>
            <p>與我們的AI專員進行互動，確保我們瞭解您的需求，提供最個性化的服務。</p>
            <a href="./selection_ai_chat.php" class="btn">
                前往與AI專員聊天
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <h2>Step 2: 讓照片動起來</h2>
            <p>我們的AI技術將您的臉型遷移至數位分身，請確認遷移結果是否滿意。</p>
            <a href="./check_action_transfer.php" class="btn">
                讓照片動起來
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <h2 class="
                <?php 
                    if ($check_step3 == 'hide') {
                        echo 'hide-title';
                    }
                ?>">
                Step 3: 檢查個人化表單內容
            </h2>
            <p>我們將為您生成一份個人化的表單，請您仔細檢查，確保所有信息準確無誤。</p>
            <p>
                <?php 
                    if ($check_step3 == 'hide') {
                        echo '❌ Step 1: 與AI專員聊天';
                    }
                    else {
                        echo '✅ Step 1: 與AI專員聊天';
                    }
                ?>
            </p>
            <a href="../../AI_Expert/front/check_list.php" class="btn 
                <?php 
                    if ($check_step3 == 'hide') {
                        echo 'hide';
                    }
                ?>">
                檢查個人化表單內容
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <h2 class="
                <?php 
                    if ($check_step4 == 'hide') {
                        echo 'hide-title';
                    }
                ?>">
                Step 4: 測試數位分身
            </h2>
            <p>與您的數位分身進行互動，測試其各種功能和表現，確保一切運作正常。</p>
            <p>
                <?php 
                    if ($check_step4_unlock == 'all') {
                        echo '❌ Step 2: 讓照片動起來<br/>';
                        echo '❌ 等待聲音克隆完成';
                    }
                    else if ($check_step4_unlock == 'transfer_video') {
                        echo '❌ Step 2: 讓照片動起來<br/>';
                        echo '✅ 等待聲音克隆完成';
                    }
                    else if ($check_step4_unlock == 'sovits_role') {
                        echo '✅ Step 2: 讓照片動起來<br/>';
                        echo '❌ 等待聲音克隆完成';
                    }
                    else {
                        echo '✅ Step 2: 讓照片動起來<br/>';
                        echo '✅ 等待聲音克隆完成';
                    }
                ?>
            </p>
            <a href="../../AI_Expert/front/family_chat.php" class="btn
                <?php 
                    if ($check_step4 == 'hide') {
                        echo 'hide';
                    }
                ?>">
                測試數位分身
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <div class="step">
            <h2 class="
                <?php 
                    if ($check_step5 == 'hide') {
                        echo 'hide-title';
                    }
                ?>">
                Step 5: 公開數位分身
            </h2>
            <p>一切準備就緒，公開您的數位分身，讓世界見證您的虛擬化身！</p>
            <p>
                <?php 
                    if ($check_step5 == 'hide') {
                        echo '❌ Step 4: 測試數位分身';
                    }
                    else {
                        echo '✅ Step 4: 測試數位分身';
                    }
                ?>
            </p>
            <a href="../../AI_Expert/front/open_family.php" class="btn
                <?php 
                    if ($check_step5 == 'hide') {
                        echo 'hide';
                    }
                ?>
            ">
                公開數位分身
                <span></span><span></span><span></span><span></span>
            </a>
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

    <script>
        // PHP 將的值傳遞給 JS
        let check_step3 = '<?php echo $check_step3; ?>';
        let check_step4 = '<?php echo $check_step4; ?>';
        let check_step4_unlock = '<?php echo $check_step4_unlock; ?>';
        let check_step5 = '<?php echo $check_step5; ?>';

        if (check_step3 == 'hide') {
            setInterval(checkAndStep3, 5000);
            // console.log('3');
        }

        else if (check_step4 == 'hide') {
            setInterval(checkAndStep4, 5000);
            // console.log('4');
        }

        else if (check_step4_unlock == 'transfer_video' || check_step4_unlock == 'sovits_role' || check_step4_unlock == 'all') {
            setInterval(checkAndStep4_Unlock, 5000);
            // console.log('5');
        }

        else if (check_step5 == 'hide') {
            setInterval(checkAndStep5, 5000);
        }

        function checkAndStep3() {
            // 使用 AJAX 從伺服器獲取最新的 check 值
            fetch('../end/get_check_step3.php')
                .then(response => response.text())
                .then(data => {
                    if (data !== check_step3) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function checkAndStep4() {
            // 使用 AJAX 從伺服器獲取最新的 check 值
            fetch('../end/get_check_step4.php')
                .then(response => response.text())
                .then(data => {
                    if (data !== check_step4) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function checkAndStep5() {
            // 使用 AJAX 從伺服器獲取最新的 check 值
            fetch('../end/get_check_step5.php')
                .then(response => response.text())
                .then(data => {
                    if (data !== check_step5) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function checkAndStep4_Unlock() {
            // 使用 AJAX 從伺服器獲取最新的 check 值
            fetch('../end/get_check_step4_unlcok.php')
                .then(response => response.text())
                .then(data => {
                    if (data !== check_step4_unlock) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>