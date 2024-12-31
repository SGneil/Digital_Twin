<?php
    session_start();
    if (!(isset($_SESSION['username']))) {
        header('Location: ../../Lobby/front/login_page.php');
    }
    else if ($_SESSION['identity'] != 'admin') {
        header('Location: ../../Lobby/front/select_service.php');
    }

    require("../end/function.php");
    $chatgpt_state = select_server_state('ChatGPT');
    $Easy_Wav2Lip_state = select_server_state('Easy-Wav2Lip');
    $GPT_SoVITS_Inference_api_state = select_server_state('GPT-SoVITS-Inference_api');
    $GPT_SoVITS_Inference_pure_api_state = select_server_state('GPT-SoVITS-Inference_pure-api');
    $GPT_SoVits_trained_state = select_server_state('GPT-SoVits-trained');
    $LivePortrait_state = select_server_state('LivePortrait');
    $Watermark_state = select_server_state('Watermark');
?>

<!DOCTYPE html>
<html>

<head>
  <title>管理API資訊</title>
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
                        <a class="nav-link" href="../../Admin/front/management_server.php">管理API</a>
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
            <h3>API列表</h3>
            <div class="back-btn">
                <a href="./management_interface.php" class="btn">
                    回上一頁
                    <span></span><span></span><span></span><span></span>
                </a>
            </div>

            <table>
                <tr class="hide-header-on-mobile">
                    <th>API名稱</th>
                    <th>API狀態</th>
                    <th>監控API</th>
                    <th>開啟API</th>
                    <th>關閉API</th>
                </tr>
                
                <!-- ChatGPT -->
                <tr class="ani-bg">
                    <td>
                        <p>ChatGPT</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($chatgpt_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($chatgpt_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./chatgpt_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('ChatGPT', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('ChatGPT', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- GPT-SoVITS-Inference_api -->
                <tr class="ani-bg">
                    <td>
                        <p>GPT-SoVITS-Inference_api</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($GPT_SoVITS_Inference_api_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($GPT_SoVITS_Inference_api_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./GPT_SoVITS_Inference_api_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVITS-Inference_api', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVITS-Inference_api', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- GPT-SoVITS-Inference_pure-api -->
                <tr class="ani-bg">
                    <td>
                        <p>GPT-SoVITS-Inference_pure-api</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($GPT_SoVITS_Inference_pure_api_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($GPT_SoVITS_Inference_pure_api_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./GPT_SoVITS_Inference_pure_api_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVITS-Inference_pure-api', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVITS-Inference_pure-api', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- GPT-SoVits-trained -->
                <tr class="ani-bg">
                    <td>
                        <p>GPT-SoVits-trained</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($GPT_SoVits_trained_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($GPT_SoVits_trained_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./GPT_SoVits_trained_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVits-trained', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('GPT-SoVits-trained', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- Easy-Wav2Lip -->
                <tr class="ani-bg">
                    <td>
                        <p>Easy-Wav2Lip</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($Easy_Wav2Lip_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($Easy_Wav2Lip_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./Easy_Wav2Lip_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('Easy-Wav2Lip', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('Easy-Wav2Lip', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- LivePortrait -->
                <tr class="ani-bg">
                    <td>
                        <p>LivePortrait</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($LivePortrait_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($LivePortrait_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./LivePortrait_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('LivePortrait', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('LivePortrait', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>

                <!-- Watermark -->
                <tr class="ani-bg">
                    <td>
                        <p>Watermark</p>
                    </td>
                    <td>
                        <p>
                            <?php
                                if ($Watermark_state['state'] == '未開啟') {
                                    echo('❌未開啟');
                                }
                                else {
                                    echo('✅已開啟');
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php if ($Watermark_state['state'] == '未開啟'): ?>
                            <button class="btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </button>
                        <?php else: ?>
                            <a href="./Watermark_log.php" class="btn">
                                監控API
                                <span></span><span></span><span></span><span></span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('Watermark', 'start')" class="btn">
                            開啟API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                    
                    <td>
                        <a href="javascript:void(0)" onclick="handleServer('Watermark', 'stop')" class="btn">
                            關閉API
                            <span></span><span></span><span></span><span></span>
                        </a>
                    </td>
                </tr>
                
            </table>

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

    <script>
        setInterval(() => checkServer('ChatGPT'), 5000);
        setInterval(() => checkServer('Easy-Wav2Lip'), 5000);
        setInterval(() => checkServer('GPT-SoVITS-Inference_api'), 5000);
        setInterval(() => checkServer('GPT-SoVITS-Inference_pure-api'), 5000);
        setInterval(() => checkServer('GPT-SoVits-trained'), 5000);
        setInterval(() => checkServer('LivePortrait'), 5000);
        setInterval(() => checkServer('Watermark'), 5000);

        function checkServer(server) {
            // 使用 AJAX 從API獲取最新的 check 值
            fetch('../end/get_server_state.php?server_name=' + server)
                .then(response => response.text())
                .then(data => {
                    // 獲取所有的 td 元素
                    const allTds = document.querySelectorAll('td');
                    let statusElement = null;
                    
                    // 尋找包含API名稱的 td，然後取得其後的狀態 td
                    for (let td of allTds) {
                        if (td.querySelector('p') && td.querySelector('p').textContent.trim() === server) {
                            statusElement = td.nextElementSibling.querySelector('p');
                            break;
                        }
                    }
                    
                    if (statusElement) {
                        const currentDisplayStatus = statusElement.textContent.includes('✅') ? '已開啟' : '未開啟';
                        
                        // 只有當資料庫狀態和顯示狀態不一致時才重新載入
                        if (data !== 'Error' && data !== currentDisplayStatus) {
                            location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function handleServer(serverName, action) {
            const url = action === 'start' ? 
                '../end/start_server.php' : 
                '../end/stop_server.php';

            fetch(`${url}?server=${serverName}`, {
                method: 'GET'
            })
            .then(response => response.text())
            .then(data => {
                // 操作完成後重新載入頁面狀態
                setTimeout(() => {
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('操作失敗，請稍後再試');
            });
        }

    </script>
</body>

</html>