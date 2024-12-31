<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    else if ($_SESSION['identity'] != 'admin') {
        header('Location: ../../Lobby/front/select_service.php');
    }
?>


<!DOCTYPE html>
<html>

<head>
  <title>ChatGPT監控介面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/log.css">
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
                        <a class="nav-link" href="../../Admin/front/management_user.php">管理使用者</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/front/management_server.php">管理伺服器</a>
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

        <div class="user-profile-container">
            <div class="select-card">
                <h4>ChatGPT監控介面</h4>
                <div>
                    <a href="./management_server.php" class="btn">
                        回上一頁
                        <span></span><span></span><span></span><span></span>
                    </a>
                    <a class="btn" id="scroll-btn">
                    開啟自動滾動
                        <span></span><span></span><span></span><span></span>
                    </a>
                </div>
                
                <br>
                <textarea id="logOutput" style="width: 1150px; height: 600px; background-color: lightblue;" readonly>載入中...</textarea>
                
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

    <script>
        function updateLog() {
            fetch('../end/get_server_log.php?server=ChatGPT')
                .then(response => response.json())
                .then(data => {
                    const textarea = document.getElementById('logOutput');
                    if(data.output && data.output.length > 0) {
                        textarea.value = data.output.join('\n');

                        let scrollBtnText = document.getElementById("scroll-btn").textContent;
                        if (scrollBtnText == '關閉自動滾動') {
                            textarea.scrollTop = textarea.scrollHeight;
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // 每秒更新一次日誌
        setInterval(updateLog, 1000);

        // 取得 a 標籤元素
        const scrollBtn = document.getElementById("scroll-btn");

        // 為按鈕添加點擊事件監聽器
        scrollBtn.addEventListener("click", function(event) {
            event.preventDefault(); // 防止默認跳轉行為
            // 判斷當前文字並切換
            if (scrollBtn.textContent.includes("關閉自動滾動")) {
                scrollBtn.textContent = "開啟自動滾動";
            } else {
                scrollBtn.textContent = "關閉自動滾動";
            }
        });
    </script>

</body>

</html>