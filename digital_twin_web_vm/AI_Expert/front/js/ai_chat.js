let ChatImg;

// 在使用 topic_id 之前先獲取它的值
const topic_id = document.getElementById('type').dataset.value;
const account_id = document.getElementById('account_id').dataset.value;
const username = document.getElementById('username').dataset.value;
const user_img = './img/default_userImg.jpg';

(function (global) {
    // 將全局變量集中在一個對象中
    const state = {
        isRecording: false,
        mediaRecorder: null,
        audioChunks: [],
        recordButton: null,
        transcript: '',
        topic_id: topic_id,
        messages: [],
        recognition: null,
        noSpeechTimeout: null,
        hasSpeech: false,
        shouldSaveAudio: true,
    };

    // 將 DOM 相關操作集中
    const DOM = {
        getRecordButton: () => document.getElementById('recordButton'),
        getChatContainer: () => document.querySelector('.chats'),
        getChatPage: () => document.querySelector('.chat-page'),
        getTextField: () => document.querySelector('#txt'),
        getSendButton: () => document.querySelector('#btn')
    };

    // 將 API 調用集中
    const API = {
        saveAudio: (formData) => fetch('../end/save_audio.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json()),
        saveChat: (formData) => fetch('../end/upload_text.php', {
            method: 'POST',
            body: formData
        }),
        sendChats: (messages) => fetch('../end/sent_chats.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ messages })
        }),
        loadMessages: (formData) => $.ajax({
            url: '../end/load_chats.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        }),
        get_topic: (formData) => fetch('../end/get_topic.php', {
            method: 'POST',
            body: formData
        }),
        deleteChats: async (topic_id, account_id) => {
            const formData = new FormData();
            formData.append('topic_id', topic_id);
            formData.append('account_id', account_id);

            try {
                const response = await fetch('../end/delete_chats.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.message) {
                    console.log(data.message);
                    loadMessagesInternal();
                    starNewChat();
                } else if (data.error) {
                    console.error(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
    };

    // 將 API 對象掛載到 global (window) 對象
    global.API = API;

    // 錄音相關函數
    function startRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('getUserMedia not supported on your browser!');
            return;
        }

        state.isRecording = false;
        state.hasSpeech = false;
        state.audioChunks = [];
        state.silenceStartTime = null;

        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const source = audioContext.createMediaStreamSource(stream);
                const analyser = audioContext.createAnalyser();
                source.connect(analyser);

                state.mediaRecorder = new MediaRecorder(stream);
                state.mediaRecorder.start();

                state.mediaRecorder.addEventListener('dataavailable', event => {
                    state.audioChunks.push(event.data);
                });

                state.mediaRecorder.addEventListener('stop', handleRecordingStop);

                state.isRecording = true;
                updateButtonState();

                // 設置1.5秒內無聲則停止錄音的計時器
                let initialSilenceTimer = setTimeout(() => {
                    if (!state.hasSpeech) {
                        console.log('1.5秒內未檢測到語音，停止錄音且不保存。');
                        stopRecording(false);
                    }
                }, 1500);

                // 實時監測音量級別
                const dataArray = new Uint8Array(analyser.fftSize);
                const silenceThreshold = 15; // 設定靜音閥值
                const silenceDuration = 2000; // 設定靜音持續時間 (毫秒)

                function detectSilence() {
                    analyser.getByteFrequencyData(dataArray);
                    let sum = dataArray.reduce((a, b) => a + b, 0);
                    let average = sum / dataArray.length;
                    
                    if (average > silenceThreshold) {
                        state.hasSpeech = true;
                        state.silenceStartTime = null;
                        clearTimeout(initialSilenceTimer);
                    } else {
                        if (!state.silenceStartTime) {
                            state.silenceStartTime = Date.now();
                        } else if (Date.now() - state.silenceStartTime > silenceDuration) {
                            console.log(`檢測到靜音超過${silenceDuration/1000}秒，停止錄音。`);
                            stopRecording();
                            return;
                        }
                    }
                    if (state.isRecording) {
                        requestAnimationFrame(detectSilence);
                    }
                }
                detectSilence();
            })
            .catch(error => {
                console.error('無法存取麥克風。', error);
            });
    }

    function stopRecording(shouldSave = true) {
        console.log('錄音停止...');
        if (state.mediaRecorder && state.isRecording) {
            state.shouldSaveAudio = shouldSave;
            state.mediaRecorder.stop();
            state.mediaRecorder.stream.getTracks().forEach(track => track.stop());
            state.isRecording = false;
            updateButtonState();
        }
    }

    function toggleRecording() {
        state.isRecording ? stopRecording() : startRecording();
    }

    function updateButtonState() {
        if (state.recordButton) {
            state.recordButton.textContent = state.isRecording ? '結束發話' : '按下發話';
        }
    }

    function handleRecordingStop() {
        if (!state.shouldSaveAudio) {
            console.log('錄音停止且不保存音頻');
            state.audioChunks = []; // 清空音頻數據
            return;
        }

        let audioBlob = new Blob(state.audioChunks, { type: 'audio/wav' });
        saveAudio(audioBlob);
        
        state.audioChunks = [];
    }

    function saveAudio(audioBlob) {
        // 補零函數
        function padZero(num) {
            return num < 10 ? '0' + num : num;
        }

        // 取得當前日期和時間
        const now = new Date();
        const year = now.getFullYear();
        const month = padZero(now.getMonth() + 1);
        const day = padZero(now.getDate());
        const hours = padZero(now.getHours());
        const minutes = padZero(now.getMinutes());
        const seconds = padZero(now.getSeconds()); 

        // 格式化輸出
        const formattedDateTime = `${year}-${month}-${day}_${hours}${minutes}${seconds}`;

        const fileName = `${formattedDateTime}.wav`

        const formData = new FormData();
        formData.append('user', username)
        formData.append('audio', audioBlob, fileName);

        API.saveAudio(formData)
            .then(data => {
                console.log('音頻保存:', data);
                if (data.stt_result && data.stt_result.result) {
                    state.transcript = data.stt_result.result;
                    console.log("語音識別結果: ", state.transcript);
                    sent_chats(state.transcript);
                } else if (data.stt_result || data.stt_result.error) {
                    console.error("保存音頻或語音識別錯誤:", data.stt_result.error);
                    showText('user1', ChatImg, '抱歉，語音識別出現問題，請稍後再試。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showText('user1', ChatImg, '抱歉，保存音頻時出現問題，請稍後再試。');
            });
    }

    async function getTTSAndPlay(text, user, img) { 
        const jsonData = {
            text: text,
            sovits_role: 'Ahong',
            save_audio_path: './output.wav'
        };

        try {
            const response = await fetch('../end/get_audio.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(jsonData),
            });

            console.log('Response status:', response.status);
            const responseText = await response.text();
            console.log('Response text:', responseText);

            if (response.ok) {
                const data = JSON.parse(responseText);
                console.log('Parsed data:', data);

                if (data.status === 'success') {
                    const audioData = atob(data.audio); // 解碼 base64
                    const audioArray = new Uint8Array(audioData.length);
                    for (let i = 0; i < audioData.length; i++) {
                        audioArray[i] = audioData.charCodeAt(i);
                    }
                    const audioBlob = new Blob([audioArray], { type: 'audio/wav' });
                    PlayAudio(audioBlob);
                    
                    showText(user, img, text); // 顯示文字
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            } else {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            console.error('Error:', error);
            console.error('Error details:', error.message);
            showText(user, img, text); // 顯示文字，即使出錯也顯示
        }
    }

    function PlayAudio(audioData) {
        const audioURL = URL.createObjectURL(audioData);

        const audioPlayer = document.getElementById('audioPlayer');
        audioPlayer.src = audioURL;
        audioPlayer.play();
    }

    // 新增 save_chat 函式
    function save_chat(role, content) {
        if (!content.trim()) {
            console.warn("內容為空，未保存對話");
            return;
        }
        const formData = new FormData();
        formData.append('account_id', account_id);
        formData.append('topic_id', topic_id);
        formData.append('role', role);
        formData.append('content', content);

        API.saveChat(formData)
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    console.log("對話上傳成功");
                } else {
                    console.error("對話上傳失敗:", data.message);
                }
            })
            .catch(error => {
                console.error("對話上傳錯誤:", error);
            });
    }

    function sent_chats(content){
        showText(username, user_img, content);
        save_chat('user',content);
        console.log(state.messages)
        state.messages.push({role: 'user', content: content});

        API.sendChats(state.messages)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // 先獲取文本內容
        })
        .then(text => {
            try {
                const data = text;
                console.log('成功');
                save_chat('gpt',data);
                state.messages.push({role: 'assistant', content: data})
                getTTSAndPlay(data, 'user1', ChatImg); // 播放音訊並顯示文字
            } catch (e) {
                console.error('解析失敗:', e);
                console.log('服務器返回:', text);
                throw new Error('服務器返回的不是有效的回應');
            }
        })
        .catch(error => {
            console.error('錯誤:', error);
            showText('user1', ChatImg, '抱歉，發生了錯誤，請稍後再試。');
        });
    }

    const showText = (user, img, text, animate = true) => {
        if (!text.trim()) { // 檢查 text 是否為空
            console.warn("內容為空，未顯示對話");
            return;
        }

        if (user !== 'user1' && user !== 'user2') {
            user = 'user2'
        } else {
            user = 'user1'
        };
        const chatHtml =
            `<div class="chat-user ${user}">
            <div class="user-img">
                <img class="ai-photo" src="${img}" alt="" />
            </div>
            <div class="user-msg">
                <p class="typing"></p>
            </div>
        </div>`;

        const chats = DOM.getChatContainer();
        const chatPage = DOM.getChatPage();

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = chatHtml;
        const newMessage = tempDiv.firstChild;

        chats.appendChild(newMessage);

        // Add typing effect
        const typingElement = newMessage.querySelector('.typing');
        if (animate) {
            typeEffect(typingElement, text);
        } else {
            typingElement.textContent = text;
            typingElement.classList.remove('typing'); // Remove typing class after complete
        }

        // 確保自動滾動到最新消息
        chatPage.scrollTop = chatPage.scrollHeight;
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

    const clearText = () => {
        const chats = DOM.getChatContainer();
        chats.innerHTML = ''; // 清空所有內容
    }


    function loadMessagesInternal() {
        state.messages = []; // 清空 state.messages
        clearText(); // 清除之前的訊息

        begin_chat().then(() => { // 確保 begin_chat 完成後再執行下一步
            // 準備要傳送的資料
            const formData = new FormData();
            formData.append('topic_id', state.topic_id);
            formData.append('account_id', account_id);

            console.log('topic_id', state.topic_id);
            console.log('account_id', account_id);
            // 發送 AJAX 請求
            API.loadMessages(formData)
                .then(response => {
                    // 檢查 response 是否包含 'message' (沒有符合記錄的情況)
                    if (response.message) {
                        // 當沒有符合的記錄，執行指定的函數
                        starNewChat();
                    } else {
                        // 有訊息時顯示訊息
                        response.forEach(message => {
                            if (message.role == 'user') {
                                showText(username, user_img, message.content, false); // 禁用動畫
                                state.messages.push({role: 'user', content: message.content})
                            } else if (message.role == 'gpt') {
                                showText('user1', ChatImg, message.content, false); // 禁用動畫
                                state.messages.push({role: 'assistant', content: message.content})
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Load messages failed:', error);
                });
        });
    }
    
    function begin_chat() {
        return new Promise((resolve, reject) => { // 將 begin_chat 修改為返回 Promise
            const formData = new FormData();
            formData.append('topic_id', state.topic_id);
            API.get_topic(formData)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // 解析 JSON 回應
                })
                .then(data => {
                    if (data.status === "success") {
                        state.messages.push({role: "system", content: data.prompt});
                        state.messages.push({role: 'user', content: "開始對話"});
                        resolve(); // 成功時 resolve
                    } else {
                        console.error("未找到提示詞:", data.message);
                        showText('user1', ChatImg, '發生了錯誤，請稍後再試。');
                        resolve(); // 即使未找到提示詞也 resolve
                    }
                })
                .catch(error => {
                    console.error('錯誤:', error);
                    showText('user1', ChatImg, '抱歉，發生了錯誤，請稍後再試。');
                    reject(error); // 發生錯誤時 reject
                });
        });
    }
    
    // 特定函數處理：當沒有訊息時執行
    function starNewChat() {
        console.log('已建立新對話');
        API.sendChats(state.messages)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // 先獲取文本容
        })
        .then(text => {
            try {
                const data = text;
                console.log('成功');
                state.messages.push({role: 'assistant', content: data})
                save_chat('gpt',data)
                // showText('user1', ChatImg, data);
                getTTSAndPlay(data,'user1',ChatImg)
            } catch (e) {
                console.error('解析失敗:', e);
                console.log('服務器返回:', text);
                throw new Error('服務器返回的不是有效的回應');
            }
        })
        .catch(error => {
            console.error('錯誤:', error);
            showText('user1', ChatImg, '抱歉，發生了錯誤，請稍後再試。');
        });
        
    }
    
    

    //////////////////////////// 頁面切換功能 ////////////////////////////

    function showMainPage() {
        // 導向到selection_ai_chat.php
        window.location.href = './selection_ai_chat.php';
    }

    // 將函式掛載到 global (window) 對象
    global.showMainPage = showMainPage;
    ////////////////////////////////////////////////////////////////
    
    // 定义 init 函数
    function init(buttonId) {
        state.recordButton = document.getElementById(buttonId);
        if (state.recordButton) {
            state.recordButton.addEventListener('click', toggleRecording);
        }
    }

    // 修改 recorder 对象
    global.recorder = {
        init: init,  // 使用我們剛剛定義的 init 函數
        start: startRecording,
        stop: stopRecording,
        toggle: toggleRecording
    };

    // 將 loadMessages 函數暴露到全局作用域
    global.loadMessages = loadMessagesInternal;

})(this);

// 在函數定義和賦值之後，添加 DOMContentLoaded 事件監聽器
document.addEventListener('DOMContentLoaded', () => {
    // 初始化主照片及縮圖
    const mainImage = document.getElementById('userImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    ChatImg = mainImage ? mainImage.src : user_img; // 確保 mainImage 存在
    // ChatImg = './img/boy.jpg';

    // 更新所有对话图片
    const updateChatImages = (newSrc) => {
        const userImages = document.querySelectorAll('.chat-user.user1 .user-img img');
        userImages.forEach((img) => {
            img.src = newSrc;
        });
    };

    // 建立縮圖點擊事件
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            if (mainImage) {
                mainImage.src = thumbnail.src;
                ChatImg = thumbnail.src;

                // 更新所有对话的图片
                updateChatImages(ChatImg);
            }
        });
    });

    // 初始化錄音鈕
    recorder.init('recordButton');
    loadMessages();

    // 新增刪除按鈕事件監聽器
    const deleteButton = document.getElementById('deleteButton');
    if (deleteButton) {
        deleteButton.addEventListener('click', () => {
            API.deleteChats(topic_id, account_id);
        });
    }
});