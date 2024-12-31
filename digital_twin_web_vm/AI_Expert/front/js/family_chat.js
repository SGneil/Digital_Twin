const btn = document.querySelector('#btn');
const txt = document.querySelector('#txt');
const loading_ani = document.getElementById('loading');
let stop = 0; // 增加全局變量 stop

document.addEventListener('DOMContentLoaded', setupMediaSwitcher);

const showText = (user, img, text, id) => {
    let chatHtml = '';
    if (user !== 'user1') {
        user = 'user2';
        chatHtml =
            `<div class="chat-user ${user}">
                <div class="user-img">
                    <img src="./img/default_userImg.jpg" alt="" />
                </div>
                <div class="user-msg">
                    <p class="typing"></p>
                </div>
            </div>`;
    } else {
        user = 'user1';
        chatHtml =
            `<div class="chat-user ${user}">
                <div class="user-img">
                    <img src="${img}" alt="" />
                    <img class="video-btn" src="../../source/video-call.png" alt="" data-id="${id}" data-type="history"/>
                </div>
                <div class="user-msg">
                    <p class="typing"></p>
                </div>
            </div>`;
    }

    const chats = document.querySelector('.chats');
    const chatPage = document.querySelector('.chat-page');

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = chatHtml;
    const newMessage = tempDiv.firstChild;

    chats.appendChild(newMessage);

    // Auto Scroll to Page Bottom
    chatPage.scrollTop = chatPage.scrollHeight - chatPage.clientHeight;

    // Add typing effect
    const typingElement = newMessage.querySelector('.typing');
    typeEffect(typingElement, text);

    // 為當前生成的圖片添加點擊事件
    const videoButton = newMessage.querySelector('.video-btn');
    if (videoButton) {
        videoButton.addEventListener('click', function () {
            
            const modal = document.getElementById('cloneModal');
            
            const dowaload = document.getElementById('downloadModal');

            get_family_video_path('history', id).then(response => {
                const value = response; // 存儲 AJAX 返回的值
                if (value == 'doing') {
                    modal.style.display = 'block';
                }
                else {
                    dowaload.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('錯誤:', error);
            });

            // 設置同意和不願意按鈕的事件
            const agreeBtn = document.getElementById('agreeBtn');
            const disagreeBtn = document.getElementById('disagreeBtn');

            // 點擊「願意」按鈕時
            agreeBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                alert("你同意生成影片，請稍等...");
                modal.style.display = 'none'; // 關閉對話框

                const type = 'history';

                // console.log(type);
                // console.log(id);

                get_family_text(type, id).then(response => {
                    const value = response; // 存儲 AJAX 返回的值
                    const video_path = document.getElementById('video-path').dataset.value;
                    const sovits_role = document.getElementById('sovits-role').dataset.value;
                    // 在這裡生成影片的程式碼
                    // console.log(value);
                    // console.log(video_path);
                    // console.log(sovits_role);
                    get_video_family(value, video_path, sovits_role, type, id);
                })
                .catch(error => {
                    console.error('錯誤:', error);
                });
            };

            // 點擊「不願意」按鈕時
            disagreeBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                modal.style.display = 'none'; // 關閉對話框
            };

            // 下載影片部分
            // const dowaload = document.getElementById('downloadModal');
            // dowaload.style.display = 'block';

            // 設置確認和取消按鈕的事件
            const downloadBtn = document.getElementById('downloadBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            // 點擊「確認」按鈕時
            downloadBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                alert("正在下載您生成的影片，請稍等...");
                dowaload.style.display = 'none'; // 關閉對話框
                
                const type = btn.getAttribute('data-type');
                
                console.log(id);
                console.log(type);
                
                get_family_video_path(type, id).then(response => {
                    const videoPath = response; // 存儲 AJAX 返回的值
                    console.log(videoPath);
                    downloadFile(videoPath); // 调用下载函数
                })
                .catch(error => {
                    console.error('錯誤:', error);
                });
            };

            // 點擊「取消」按鈕時
            cancelBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                dowaload.style.display = 'none'; // 關閉對話框
            };
        });
    }
};

const typeEffect = (element, text, delay = 100) => {
    let i = 0;
    const interval = setInterval(() => {
        element.textContent += text.charAt(i);
        i++;
        if (i >= text.length) {
            clearInterval(interval);
            element.classList.remove('typing'); // Remove typing class after complete
        }
    }, delay);
};

function get_gpt_api(text, family_picture, family_video, sovits_role) {
    const formData = new FormData();
    formData.append('msg', text);

    showLoading();

    $.ajax({
        url: '../end/get_gpt_api.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);

            // 對話存進資料庫
            let id; // 定義 id 變數

            save_text(response, 'gpt').then(data => {
                id = data; // 將 response 賦值給 id
                // console.log("ID:", id); // 在這裡可以檢查 id 的值

                get_audio(response, family_picture, family_video, sovits_role, id);
            }).catch(error => {
                console.error('錯誤:', error); // 錯誤處理
            });
        },
        error: function (xhr, status, error) {
            console.log('Upload failed');
            // showText('user1', `./family/${family_picture}`, '上傳錯誤請檢查內容');
        }
    });
}

function save_text(text, role) {
    const formData = new FormData();
    formData.append('text', text);
    formData.append('role', role);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../end/upload_test_chat_text.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                resolve(response); // 將響應傳遞回去
            },
            error: function (xhr, status, error) {
                reject(error); // 拋出錯誤
            }
        });
    });
}


function get_audio(text, picture_path, video_path, sovits_role, id) {
    const formData = new FormData();
    const talk_video_folder = document.getElementById('talk_video_folder').dataset.value;
    formData.append('text', text);
    formData.append('sovits_role', sovits_role);
    formData.append('video_path', video_path);
    formData.append('talk_video_folder', talk_video_folder);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../end/get_test_chat_audio.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: async function (response) {
                console.log(response);

                hideLoading();
                txt.value = '';
                txt.disabled = false;
                playAudio(response);
                showText('user1', picture_path, text, id);
                resolve();
            },
            error: function (xhr, status, error) {
                console.log('Upload failed');
                reject(error);
            }
        });
    });
}

function playAudio(filepath) {
    const audio = new Audio(filepath);
    audio.play();
}

function get_video_family(text, video_path, sovits_role, type, id) {
    showLoading();
    const formData = new FormData();
    const talk_video_folder = document.getElementById('talk_video_folder').dataset.value;
    formData.append('text', text);
    formData.append('sovits_role', sovits_role);
    formData.append('video_path', video_path);
    formData.append('talk_video_folder', talk_video_folder);
    formData.append('type', type);
    formData.append('id', id);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../end/get_video.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: async function (response) {
                // console.log(response);
                hideLoading();
                loadAndPlayVideo(response);
                resolve();
                txt.value = '';
                txt.disabled = false;
                // upload_video_path(type, id, response);
            },
            error: function (xhr, status, error) {
                console.log('Upload failed');
                reject(error);
            }
        });
    });
}

// 取得文字
function get_family_text(type, id) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('type', type);
        formData.append('id', id);

        $.ajax({
            url: '../end/get_test_chat_text.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                resolve(response); // 返回請求結果
            },
            error: function (xhr, status, error) {
                reject(error); // 發生錯誤時
            }
        });
    });
}

function get_family_video_path(type, id) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('type', type);
        formData.append('id', id);

        $.ajax({
            url: '../end/get_test_chat_video_path.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                resolve(response); // 返回請求結果
            },
            error: function (xhr, status, error) {
                reject(error); // 發生錯誤時
            }
        });
    });
}

function showLoading() {
    loading_ani.style.display = 'block';
}

function hideLoading() {
    loading_ani.style.display = 'none';
}

function setupMediaSwitcher() {
    const videoElement = document.getElementById('userVideo');
    const imageElement = document.getElementById('userImage');

    function loadAndPlayVideo(videoSrc) {
        videoElement.src = videoSrc;
        imageElement.style.display = 'none';
        videoElement.style.display = 'block';
        videoElement.load();
        videoElement.play();
    }

    videoElement.addEventListener('ended', () => {
        videoElement.style.display = 'none';
        imageElement.style.display = 'block';
    });

    window.loadAndPlayVideo = loadAndPlayVideo;
}

function downloadFile(filePath) {
    // 创建一个临时的 <a> 标签
    const downloadLink = document.createElement('a');
    downloadLink.href = filePath; // 设置文件路径

    // 提取文件名作为下载的文件名
    const fileName = filePath.split('/').pop();
    downloadLink.download = fileName;

    // 将 <a> 标签添加到文档中并点击它以触发下载
    document.body.appendChild(downloadLink);
    downloadLink.click();

    // 下载完后，移除临时的 <a> 标签
    document.body.removeChild(downloadLink);
}

btn.addEventListener('click', () => {
    const value = txt.value;

    if (value !== '' && value !== '思考中~') {
        txt.value = '思考中~';
        txt.disabled = true;
        const img_path = document.getElementById('img-path').dataset.value;

        const video_path = document.getElementById('video-path').dataset.value;

        const sovits_role = document.getElementById('sovits-role').dataset.value;

        // 顯示子孫文字
        showText('user2', '../../FamiliaLyrica/front/img/default_userImg.jpg', value, 'no');

        // 對話存進資料庫
        save_text(value, 'user');

        // 取得長輩GPT文字
        get_gpt_api(value, img_path, video_path, sovits_role);
    }
});

txt.addEventListener('keypress', (event) => {
    if (event.key === 'Enter') {
        const value = txt.value;

        if (value !== '' && value !== '思考中~') {
            txt.value = '思考中~';
            txt.disabled = true;
            const img_path = document.getElementById('img-path').dataset.value;

            const video_path = document.getElementById('video-path').dataset.value;

            const sovits_role = document.getElementById('sovits-role').dataset.value;

            // 顯示子孫文字
            showText('user2', img_path, value, 'no');

            // 對話存進資料庫
            save_text(value, 'user');

            // 取得並顯示長輩gpt文字
            get_gpt_api(value, img_path, video_path, sovits_role);
        }
    }
});

// 動態綁定所有 class 為 'video-btn' 的圖片的點擊事件
function bindVideoButtonEvents() {
    const videoButtons = document.querySelectorAll('.video-btn');
    videoButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            
            const modal = document.getElementById('cloneModal');
            const dowaload = document.getElementById('downloadModal');
            const type = btn.getAttribute('data-type');
            const id = btn.getAttribute('data-id');

            get_family_video_path(type, id).then(response => {
                const value = response; // 存儲 AJAX 返回的值
                if (value == 'doing') {
                    modal.style.display = 'block';
                }
                else {
                    dowaload.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('錯誤:', error);
            });


            // 生成影片部分
            // 設置同意和不願意按鈕的事件
            const agreeBtn = document.getElementById('agreeBtn');
            const disagreeBtn = document.getElementById('disagreeBtn');

            // 點擊「願意」按鈕時
            agreeBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                alert("你同意生成影片，請稍等...");
                modal.style.display = 'none'; // 關閉對話框

                const type = btn.getAttribute('data-type');
                const id = btn.getAttribute('data-id');

                get_family_text(type, id).then(response => {
                    const value = response; // 存儲 AJAX 返回的值
                    const video_path = document.getElementById('video-path').dataset.value;
                    const sovits_role = document.getElementById('sovits-role').dataset.value;
                    get_video_family(value, video_path, sovits_role, type, id);
                })
                .catch(error => {
                    console.error('錯誤:', error);
                });
            };

            // 點擊「不願意」按鈕時
            disagreeBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                modal.style.display = 'none'; // 關閉對話框
            };

            // 下載影片部分

            // 生成影片部分
            // const dowaload = document.getElementById('downloadModal');
            // dowaload.style.display = 'block';

            // 設置確認和取消按鈕的事件
            const downloadBtn = document.getElementById('downloadBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            // 點擊「確認」按鈕時
            downloadBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                alert("正在下載您生成的影片，請稍等...");
                dowaload.style.display = 'none'; // 關閉對話框
                
                const type = btn.getAttribute('data-type');
                const id = btn.getAttribute('data-id');
                
                get_family_video_path(type, id).then(response => {
                    const videoPath = response; // 存儲 AJAX 返回的值
                    downloadFile(videoPath); // 调用下载函数
                })
                .catch(error => {
                    console.error('錯誤:', error);
                });
            };

            // 點擊「取消」按鈕時
            cancelBtn.onclick = function (event) {
                event.preventDefault(); // 防止默認行為
                dowaload.style.display = 'none'; // 關閉對話框
            };
        });
    });
}

let waitingButtonsCount = 0; // 全局變量，用於跟踪 "waiting" 按鈕的數量

// 查看有沒有正在生成的檔案
function checkdoingvideo(btn) {
    const type = btn.getAttribute('data-type');
    const id = btn.getAttribute('data-id');

    const checkVideoStatus = () => {
        get_family_video_path(type, id)
            .then(response => {
                const value = response; // 存儲 AJAX 返回的值
                console.log(value);
                if (value === 'waiting') {
                    if (waitingButtonsCount === 0) {
                        showLoading(); // 只有當前沒有其他 "waiting" 狀態時才顯示 loading
                        txt.value = '思考中~';
                        txt.disabled = true;
                    }
                    waitingButtonsCount = id; // 增加等待按鈕的計數
                } else {
                    if (waitingButtonsCount == id) {
                        hideLoading();
                        waitingButtonsCount = 0;
                        txt.value = '';
                        txt.disabled = false;
                        get_family_video_path(type, id).then(response => {
                            const videoPath = response; // 存儲 AJAX 返回的值
                            loadAndPlayVideo(videoPath); // 调用下载函数
                        })
                        bindVideoButtonEvents();
                    }
                    clearInterval(intervalId); // 當 value 不等於 'waiting' 時，停止檢查
                }
            })
            .catch(error => {
                console.error('錯誤:', error);
                clearInterval(intervalId); // 如果發生錯誤，也停止檢查
            });
    };

    // 立即檢查一次
    checkVideoStatus();

    // 每隔 3 秒檢查一次
    const intervalId = setInterval(checkVideoStatus, 3000);
}


// 在 DOMContentLoaded 後綁定事件
document.addEventListener('DOMContentLoaded', () => {

    const videoButtons = document.querySelectorAll('.video-btn');
    videoButtons.forEach(function (btn) {
        checkdoingvideo(btn); // 對每個按鈕進行檢查
    });
    get_video_state();
    
});

// 取得影片狀態
function get_video_state() {
    const formData = new FormData();

    $.ajax({
        url: '../end/get_video_state.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            if (response == 0) {
                bindVideoButtonEvents();
            }
        },
        error: function (xhr, status, error) {
            // reject(error); // 發生錯誤時
            console.log("失敗");
        }
    });
}