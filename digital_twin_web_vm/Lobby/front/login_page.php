<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: ../front/select_service.php');
    }
?>


<!DOCTYPE html>
<html>

<head>
  <title>登入</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="title">
        <p>利用 AI 技術生成長輩虛擬化身 - 建立跨越時空的親屬互動</p>
    </div>

    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="./select_service.php">Virtual Household：虛擬家庭</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">三</span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./login_page.php">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./register_page.php">註冊</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./forget_password_page.php">忘記密碼</a>
                    </li>
                </ul>

                <div class="navbar-icons-bottom d-lg-none">
                    <a href="./select_service.php" class="navbar-icon-link">
                        <i class="fas fa-user"></i>
                    </a>
                </div>

            </div>

            <div class="navbar-icons d-none d-lg-flex">
                <a href="./select_service.php" class="navbar-icon-link">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- 登入卡片介面 -->
    <div class="card-bg">
        <div class="login-container">
            <form action="../end/login.php" method="post" class="login-form">
                <h2>登入</h2>
                <p>
                    <?php
                        if (isset($_SESSION['login_msg'])) {
                            echo ("帳號或密碼錯誤!!");
                        }
                        else {
                            echo ("");
                        }
                        if (isset($_GET['msg'])) {
                            echo ("註冊成功請登入~");
                        }
                        else {
                            echo ("");
                        }
                    ?>
                </p>
                <div class="input-group">
                    <label for="username">使用者:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">密碼:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-links">
                    <a href="./forget_password_page.php">忘記密碼?</a>
                    <span> | </span>
                    <a href="./register_page.php">註冊</a>
                </div>
                <button class="login-btn" type="submit">登入</button>
            </form>
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