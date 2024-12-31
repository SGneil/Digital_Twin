<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    require("../end/function.php");
    $familyData = select_family_information($_SESSION['username']);
?>

<!DOCTYPE html>
<html>

<head>
  <title>查看親人資料</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/set_information.css">
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

    <!-- 表單 -->
    <div class="table-bg">
        <div class="table-size">
            <h3>親人聊天室</h3>
            <div class="enter-code">
                <input type="text" id="codeInput" placeholder="請輸入代碼">
                <a id="submitCodeBtn" class="btn">
                    加入
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
            <?php if ($familyData): ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>姓名</th>
                        <th>親人</th>
                        <th>預覽</th>
                        <th>前往與親人對話</th>
                        <th>刪除資訊</th>
                    </tr>
                    <?php foreach($familyData as $family): ?>
                    <tr  class="ani-bg">
                        <td><?php echo htmlspecialchars($family["username"]);?></td>
                        <td class="product-image">
                            <div class="img-wrapper">
                                <img src="../../<?php echo($family['picture_path']);?>">
                            </div>
                        </td>
                        <td>
                            <div class="img-wrapper">
                                <video src="../../<?php echo htmlspecialchars($family['transfer_video']); ?>" controls loop onerror="showError(this)"></video>
                            </div>
                        </td>
                        <td>
                            <a href="./family_chat.php?binding_code=<?php echo htmlspecialchars($family['binding_code']); ?>" class="btn" id="family-chat">
                                前往與親人對話
                                <span></span><span></span><span></span><span></span>
                            </a>
                        </td>
                        <td>
                            <a id="deleteBtn" class="btn">
                                刪除資訊
                                <span></span><span></span><span></span><span></span>
                            </a>
                            <div data-descendants-id="<?php echo htmlspecialchars($family['descendants_id']);?>" class="descendants-id"></div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>姓名</th>
                        <th>親人</th>
                        <th>預覽</th>
                        <th>前往與親人對話</th>
                        <th>刪除資訊</th>
                    </tr>
                    <tr class="no-product">
                        <td colspan="5">未有親人資訊!!</td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
        
    </div>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <!-- 傳php值，到js用 -->
    <div id="binding_code_id" data-value="<?php echo htmlspecialchars($family['binding_code_id']);?>"></div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/set_information.js"></script>
</body>

</html>