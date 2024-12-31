<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    require("../end/function.php");
    $familyData = select_family_information($_SESSION['username']);

    $family_check = load_family($_SESSION['username']);
    
    $check = load_video_state($_SESSION['username']);
?>

<!DOCTYPE html>
<html>

<head>
  <title>查看親人資料</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/check_action_transfer.css">
</head>

<body>
    <div class="title">
        <p>[1] 與AI專員聊天 ➡️ <span class="text-color">[2] 讓照片動起來</span> ➡️ [3]檢查個人化表單內容 ➡️ [4] 測試數位分身 ➡️ [5] 公開數位分身</p>
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
            <h1>讓照片動起來</h1>
            <?php if ($familyData): ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>
                            <h4>上傳的圖片</h4>
                        </th>
                        <th>
                            <h4>生成的影片</h4>
                        </th>
                        <th>
                            <h4>滿意</h4>
                        </th>
                        <th>
                            <h4>不滿意</h4>
                        </th>
                    </tr>
                    <?php foreach($familyData as $family): ?>
                    <tr  class="ani-bg">
                        <td>
                            <div class="img-wrapper">
                                <img src="../../<?php echo($family['picture_path']);?>">
                            </div>
                        </td>
                        <td>
                            <?php if ($family['video_state'] == "已準備好"): ?>
                                <div class="img-wrapper">
                                    <video src="../../<?php echo htmlspecialchars($family['video_path']); ?>" controls loop onerror="showError(this)"></video>
                                </div>
                            <?php else: ?>
                                <h2>生成臉部表情影片中😎</h2>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($family['video_state'] == "已準備好"): ?>
                                <h5>點擊確認滿意生成結果，之後會返回主頁。</h5>
                                <a href="javascript:void(0);" onclick="updata(<?php echo $family['id']; ?>)" class="btn">
                                    滿意<span></span><span></span><span></span><span></span>
                                </a>
                            <?php else: ?>
                                <a href="javascript:void(0);" class="btn">
                                    等待生成中~
                                    <span></span><span></span><span></span><span></span>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($family['video_state'] == "已準備好"): ?>
                                <h5>點擊確認不滿意，之後會請您重新上傳圖片並重新生成影片。</h5>
                                <a href="javascript:void(0);" onclick="delete_picture(<?php echo $family['id']; ?>)" class="btn">
                                    不滿意<span></span><span></span><span></span><span></span>
                                </a>
                            <?php else: ?>
                                <a href="javascript:void(0);" class="btn">
                                    等待生成中~
                                    <span></span><span></span><span></span><span></span>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php if ($family_check['video_state'] == "未準備好"): ?>
                    <div class="btn-back">
                        <a class="btn mt-3" href="preview_selection.php">
                            返回主頁
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </div>
                <?php endif; ?>
            <!-- <div class="btn-back">
                <a class="btn mt-3" href="preview_selection.php">
                    返回主頁
                    <span></span><span></span><span></span><span></span>
                </a>
            </div> -->
            <?php else: ?>
                <table>
                    <tr class="hide-header-on-mobile">
                        <th>
                            <h4>上傳的圖片</h4>
                        </th>
                        <th>
                            <h4>生成的影片</h4>
                        </th>
                        <th>
                            <h4>滿意</h4>
                        </th>
                        <th>
                            <h4>不滿意</h4>
                        </th>
                    </tr>
                    <tr class="no-product">
                        <td colspan="4">
                            <div class="step">
                                <a href="./upload_picture.php" class="btn">
                                    前往上傳個人照片
                                    <span></span><span></span><span></span><span></span>
                                </a>
                            </div>
                            <div>
                                <a class="btn mt-3" href="preview_selection.php">
                                    返回主頁
                                    <span></span><span></span><span></span><span></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- 彈框 -->
    <!-- <div id="cloneModal" class="modal">
        <div class="modal-content">
            <h2>聲音克隆生成中...</h2>
        </div>
    </div> -->

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/check_action_transfer.js"></script>
    <script>
        // PHP 將 check 的值傳遞給 JS
        let check = '<?php echo $check; ?>';

        // 初始檢查
        if (check == '未準備好') {
            checkAndShowModal();
        }
        
        // 每5秒檢查一次
        if (check == '未準備好') {
            setInterval(checkAndShowModal, 5000);
        }

        function checkAndShowModal() {
            // 使用 AJAX 從伺服器獲取最新的 check 值
            fetch('../end/get_video_status.php')
                .then(response => response.text())
                .then(data => {
                    if (data == '已準備好') {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>
