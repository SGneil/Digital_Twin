<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    require('../end/function.php');
    $family = load_fmaily_chat($_SESSION['username']);

    // 載入歷史對話紀錄
    $chat_history = load_test_chat_history($_SESSION['username']);

    // if ($family['transfer_video'] == 'doing') {
    //     $check = 'transfer-video-doing';
    // }
    // else {
    //     $check = 'yes';
    // }
    
?>

<!DOCTYPE html>
<html>

<head>
    <title>與親人聊天</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" href="./css/main.css">

    <link rel="stylesheet" href="./css/family_chat.css" />
    <!-- Google Fonts Links - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>


</head>

<body>
    <div class="title">
        <p>[1] 與AI專員聊天 ➡️ [2]檢查個人化表單內容 ➡️ [3] 讓照片動起來 ➡️ <span class="text-color">[4] 測試數位分身</span> ➡️ [5] 公開數位分身</p>
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

    <!-- 聊天室 -->
    <div class="container chat-bg">
      <div class="container-left">   
        <div id="loading" style="display: none;">
          <img src="../../source/loading.gif"/>
        </div>

        <img id="userImage" src="<?php echo htmlspecialchars('../..' . $family['picture_path']);?>">

        <video id="userVideo" style="display: none;"></video>

        <div class="go-back">
            <a href="../../AI_Expert/front/preview_selection.php" class="btn">
                回上一頁
                <span></span><span></span><span></span><span></span>
            </a>
        </div>
      </div>

      <div class="container-right">
        <div class="wrapper">
          <div class="header">
            <span class="material-symbols-outlined"> menu </span>
            <p>線上客服</p>
            <span class="material-symbols-outlined"> filter_alt </span>
          </div>
          <div class="chat-page">
            <div class="chats"> 
                <div class="chat-user user1">
                    <div class="user-img">
                        <img src="<?php echo htmlspecialchars('../..' . $family['picture_path']);?>" alt="" />
                        <img class="video-btn" src="../../source/video-call.png" alt="" class="video-btn" data-type="<?php echo ('open');?>" data-id="<?php echo htmlspecialchars($family['family_chat_prompt_id']);?>"/>
                    </div>
                    <div class="user-msg">
                        <p><?php echo htmlspecialchars($family['gpt_prompt']);?></p>
                        <!-- <span class="time">未知時間</span> -->
                    </div>
                </div>  
                
                <?php if ($chat_history): ?>
                    
                    <?php foreach($chat_history as $history): ?>
                        
                        <?php if ($history['role'] == 'gpt'): ?>
                            
                            <div class="chat-user user1">
                                <div class="user-img">
                                    <img src="<?php echo htmlspecialchars('../..' . $family['picture_path']);?>" alt="" />
                                    <img class="video-btn" src="../../source/video-call.png" alt="" class="video-btn" data-type="<?php echo ('history');?>" data-id="<?php echo htmlspecialchars($history['id']);?>"/>
                                </div>
                                <div class="user-msg">
                                    <p><?php echo htmlspecialchars($history['text']);?></p>
                                    <!-- <span class="time">未知時間</span> -->
                                </div>
                            </div>

                        <?php else: ?>

                            <div class="chat-user user2">
                                <div class="user-img">
                                    <img src="./img/default_userImg.jpg" alt="" />
                                </div>
                                <div class="user-msg">
                                    <p><?php echo htmlspecialchars($history['text']);?></p>
                                    <!-- <span class="time">未知時間</span> -->
                                </div>
                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
          </div>
          <div class="chat-input">
            <img src="<?php echo htmlspecialchars('../..' . $family['picture_path']);?>" alt="" />
            <input type="text" placeholder="Type Message..." id="txt" />
            <label for="txt" id="btn">Send</label>
          </div>
        </div>
      </div>
    </div>

    <!-- 彈框 -->
    <div id="family_information_Modal" class="modal">
        <div class="modal-content">
            <h2>請先讓照片動起來...</h2>
            <a href="../../AI_Expert/front/check_action_transfer.php" class="btn">
                前往讓照片動起來
                <span></span><span></span><span></span><span></span>
            </a>
        </div>
    </div>

    <!-- 影片生成彈框 -->
    <div id="cloneModal" class="modal">
        <div class="modal-content">
            <h2>生成親人說話影片需要等待，請問是否願意?</h2>
            <div class="btn-position">
                <a class="btn" id="agreeBtn">
                    願意
                    <span></span><span></span><span></span><span></span>
                </a>
                <a class="btn" id="disagreeBtn">
                    不願意
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
        </div>
    </div>

    <!-- 下載影片彈框 -->
    <div id="downloadModal" class="modal">
        <div class="modal-content">
            <h2>是否下載影片?</h2>
            <div class="btn-position">
                <a class="btn" id="downloadBtn">
                    確定
                    <span></span><span></span><span></span><span></span>
                </a>
                <a class="btn" id="cancelBtn">
                    取消
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>
        </div>
    </div>

    <!-- 回到頂部按鈕 -->
    <button id="back-to-top" onclick="scrollToTop()">⬆</button>

    <!-- 傳php值，到js用 -->
    <div id="img-path" data-value="<?php echo htmlspecialchars('../..' . $family['picture_path']);?>"></div>
    <div id="video-path" data-value="<?php echo htmlspecialchars('../..' . $family['transfer_video']); ?>"></div>
    <div id="sovits-role" data-value="<?php echo htmlspecialchars($family['sovits_role']); ?>"></div>
    <div id="talk_video_folder" data-value="<?php echo htmlspecialchars($family['talk_video_folder']); ?>"></div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/family_chat.js"></script>
    <script>
        // PHP 將 check 的值傳遞給 JS
        const check = '<?php echo $check; ?>';

        // 如果 check 等於 'doing'，顯示彈框
        if (check === 'transfer-video-doing') {
            const modal = document.getElementById('family_information_Modal');
            modal.style.display = 'block';
        }
    </script>
</body>

</html>