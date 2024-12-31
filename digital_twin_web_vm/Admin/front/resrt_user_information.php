<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    else if ($_SESSION['identity'] != 'admin') {
        header('Location: ../../Lobby/front/select_service.php');
    }

    require("../end/function.php");
    $user = load_user($_GET['account_id']);
?>


<!DOCTYPE html>
<html>

<head>
  <title>使用者資訊</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/resrt_user_information.css">
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

    <!-- 登入卡片介面 -->
    <div class="card-bg">
        <div class="login-container">
            <form action="../end/reset.php" method="post" class="login-form">
                <h2>
                    <?php echo htmlspecialchars($user["username"]);?> 的資訊
                    <a href="./management_user.php" class="btn">
                        回上一頁
                        <span></span><span></span><span></span><span></span>
                    </a>
                </h2>
                <div class="input-group">
                    <label for="username">名稱:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user["username"]);?>" readonly>
                </div>
                <div class="input-group">
                    <label for="password">密碼:</label>
                    <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($user["password"]);?>" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user["email"]);?>" required>
                </div>
                <div class="input-group">
                    <label for="binding_code">邀請碼:</label>
                    <input type="text" id="binding_code" name="binding_code" value="<?php echo htmlspecialchars($user["binding_code"]);?>">
                </div>
                <div class="input-group">
                    <label for="identity">身分:(一般用戶:user、管理員:admin)</label>
                    <input type="text" id="identity" name="identity" value="<?php echo htmlspecialchars($user["identity"]);?>" required>
                </div>
                <button class="login-btn" type="submit">確認修改</button>
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