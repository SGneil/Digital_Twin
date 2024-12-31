<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    else if ($_SESSION['identity'] != 'admin') {
        header('Location: ../../Lobby/front/select_service.php');
    }

    require("../end/function.php");
    $accountData = select_account();
?>

<!DOCTYPE html>
<html>

<head>
  <title>管理使用者資訊</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/management_user.css">
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

    <!-- 表單 -->
    <div class="table-bg">
        <div class="table-size">
            <h3>使用者列表</h3>
            <div class="back-btn">
                <a href="./management_interface.php" class="btn">
                    回上一頁
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
            
            <!-- <div class="enter-code">
                <input type="text" id="codeInput" placeholder="請輸入代碼">
                <a id="submitCodeBtn" class="btn">
                    加入
                    <span></span><span></span><span></span><span></span>
                </a>
            </div> -->
            <?php if ($accountData): ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>姓名</th>
                        <th>帳戶身分</th>
                        <th>邀請碼</th>
                        <th>修改基本資料</th>
                        <th>查看對話紀錄</th>
                        <th>刪除帳戶</th>
                    </tr>
                    <?php foreach($accountData as $account): ?>
                    <tr class="ani-bg">
                        <td>
                            <?php echo htmlspecialchars($account["username"]);?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($account["identity"]);?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($account["binding_code"]);?>
                        </td>
                        
                        <td>
                            <a href="./resrt_user_information.php?account_id=<?php echo htmlspecialchars($account["account_id"]);?>" class="btn" id="family-chat">
                                修改基本資料
                                <span></span><span></span><span></span><span></span>
                            </a>
                        </td>
                        <td>
                            <a href="./check_ai_chat_history.php?account_id=<?php echo htmlspecialchars($account["account_id"]);?>" id="chatBtn" class="btn mt-3">
                                AI 專員
                                <span></span><span></span><span></span><span></span>
                            </a>
                            <a href="./check_family_chat_history.php?account_id=<?php echo htmlspecialchars($account["account_id"]);?>" id="chatBtn" class="btn mt-3">
                                親人對話
                                <span></span><span></span><span></span><span></span>
                            </a>
                        </td>
                        <td>
                            <a class="btn" href="../end/delete_account.php?account_id=<?php echo htmlspecialchars($account['account_id']);?>&username=<?php echo htmlspecialchars($account['username']);?>">
                                刪除帳戶
                                <span></span><span></span><span></span><span></span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>姓名</th>
                        <th>帳戶身分</th>
                        <th>邀請碼</th>
                        <th>修改基本資料</th>
                        <th>查看對話紀錄</th>
                        <th>刪除帳戶</th>
                    </tr>
                    <tr class="no-product">
                        <td colspan="6">未有使用者資訊!!</td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
        
    </div>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="./js/home.js"></script>
</body>

</html>