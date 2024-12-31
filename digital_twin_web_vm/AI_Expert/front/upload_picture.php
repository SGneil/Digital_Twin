<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }

    require('../end/function.php');
    $check = check_photo($_SESSION['username']);
    if ($check == 'YES') {
        header('Location: ../../AI_Expert/front/check_action_transfer.php');
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
    <link rel="stylesheet" href="./css/upload_picture.css">
</head>

<body>
    <div class="title">
        <p>[1] 與AI專員聊天 ➡️ <span class="text-color">[2] 讓照片動起來</span> ➡️ [3]檢查個人化表單內容 ➡️ [4] 測試數位分身➡️  [5] 公開數位分身</p>
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
            <form id="uploadForm" class="login-form" enctype="multipart/form-data">
                <h2>上傳照片</h2>
                <div class="input-group">
                    <label for="family_image">上傳的照片:<br>(請上傳2M以下，格式為jpg/png)</label>
                    <input type="file" id="family_image" name="family_image" required>
                </div>
                <div id="previewContainer">
                    <img id="previewImage" src="" alt="图片预览">
                </div>
                <button id="uploadButton" class="login-btn" type="submit">確認上傳~</button>
            </form>
        </div>
    </div>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/upload_picture.js"></script>
    <script>
        $(document).ready(function () {
            // 监听表单提交事件
            $('#uploadForm').submit(function (event) {
                // 阻止表单默认提交行为
                event.preventDefault();

                // 创建 FormData 对象
                var formData = new FormData(this);
                // 发送 AJAX 请求上传文件
                $.ajax({
                    url: '../end/upload_picture.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // 告诉 jQuery 不要处理发送的数据
                    contentType: false, // 告诉 jQuery 不要设置 content-type 头部
                    success: function (response) {
                        // console.log('Upload successful');
                        
                        if (response != '請上傳小於2M的圖片或正確格式的檔案') {
                            alert('上傳成功');
                            window.location.href = '../front/check_action_transfer.php';
                        }
                        else {
                            alert(response);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('請上傳小於2M的圖片或正確格式的檔案');
                    }
                });
            });
        });
    </script>
</body>

</html>
