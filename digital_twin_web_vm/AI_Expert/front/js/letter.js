(() => {
    // 定義全局狀態對象
    const state = {
        username: document.getElementById('username').dataset.value,
        account_id: document.getElementById('account_id').dataset.value,
        topic_id: 6,
    };

    const modalsContainer = document.getElementById('modalsContainer');

    // 定義 API 對象，包含所有與後端通信的方法
    const API = {
        // 獲取用戶信息
        async getInfo(account_id) {
            const formData = new FormData();
            formData.append('account_id', account_id);
            const response = await fetch('../end/get_info.php', { method: 'POST', body: formData });
            return response.json();
        },

        // 獲取對話摘要
        async conversation_summary(topic_id, account_id) {
            const formData = new FormData();
            formData.append('topic_id', topic_id);
            formData.append('account_id', account_id);
            const response = await fetch('../end/conversation_summary.php', { method: 'POST', body: formData });
            const json = await response.json();
            return json.letter;
        },

        // 保存用戶信息
        async saveUserInfo(formData) {
            const response = await fetch('../end/save_user_info.php', { method: 'POST', body: formData });
            return response.json();
        },

        // 執行產生用戶提示詞
        async generatePrompt(formData) {
            const response = await fetch('../end/generate_prompt.php', { method: 'POST', body: formData });
            return response.json();
        },
        callSentVoiceAPI: async (username, account_id, topic_id) => {
            const formData = new FormData();
            formData.append('username', username);
            formData.append('account_id', account_id);
            formData.append('topic_id', topic_id);

            try {
                const response = await fetch('../end/call_SentVoiceAPI.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.text();
                console.log("SentVoiceAPI 響應:", result);
                return result;
            } catch (error) {
                console.error('SentVoiceAPI 錯誤:', error);
                throw error;
            }
        }
    };

    // 將 API 對象掛載到全局 window 對象上，以便其他腳本可以訪問
    window.API = API;

    // 加載用戶信息的異步函數
    async function loadUserInfo() {
        console.log("開始加載用戶信息");
        try {
            // 嘗試獲取用戶信息
            const data = await API.getInfo(state.account_id);
            if (data.success && data.data.letter) {
                // 如果成功獲取到 letter，解析並填充表單
                const letter = JSON.parse(data.data.letter);
                fillForm(letter); // 將 letter 包裝在一個對象中
            } else {
                // 如果沒有獲取到 letter，執行對話摘要
                console.log("沒有用戶資料或 letter 為空，執行 conversation_summary");
                const summaryData = await API.conversation_summary(state.topic_id, state.account_id);
                fillForm(summaryData);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("加載用戶信息時發生錯誤，請稍後再試。");
        }
    }

    // 填充表單的函數
    function fillForm(data) {
        console.log("開始填充表單");
        console.log("收到的數據:", data);

        const numberOfItems = data.length;
        console.log("numberOfItems: ", numberOfItems);

        // 清空現有的內容
        modalsContainer.innerHTML = '';

        // 為每個信件項目創建輸入框
        data.forEach((item, index) => {
            const itemContainer = document.createElement('div');
            itemContainer.className = 'mb-4';
            itemContainer.innerHTML = `
                <h5>信件 ${index + 1}</h5>
                <div class="input-group mb-3">
                    <label for="recipient${index + 1}" class="form-label me-2">收件人：</label>
                    <input type="text" class="form-control" id="recipient${index + 1}" value="${item.recipient || ''}">
                </div>
                <div class="input-group">
                    <label for="content${index + 1}" class="form-label me-2">信件內容：</label>
                    <textarea class="form-control" id="content${index + 1}" rows="4">${item.content || ''}</textarea>
                </div>
            `;
            modalsContainer.appendChild(itemContainer);
        });
    }

    // 保存表單數據的異步函數
    async function saveFormData() {
        console.log("開始保存表單數據");
        const formData = new FormData();
        
        // 收集所有信件項目的內容
        const letter = Array.from(modalsContainer.children).map(child => ({
            recipient: child.querySelector('input[id^="recipient"]').value,
            content: child.querySelector('textarea[id^="content"]').value
        }));

        formData.append('account_id', state.account_id);
        formData.append('topic_id', state.topic_id);
        formData.append('letter', JSON.stringify(letter));

        try {
            // 嘗試保存用戶信息
            const data = await API.saveUserInfo(formData);
            console.log("保存結果:", data);
            if (data.success) {
                alert("資料已成功保存！");
                generatePrompt();
                window.location.href = './check_list.php';
            } else {
                alert("保存失敗：" + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("保存時發生錯誤，請稍後再試。");
        }
    }

    // 修改 generatePrompt 函數
    async function generatePrompt() {
        const formData = new FormData();
        const data = await API.getInfo(state.account_id);
        formData.append('account_id', state.account_id);
        formData.append('data', JSON.stringify(data.data));
        const response = await API.generatePrompt(formData);
        return response;
    }

    function callSentVoiceAPI() {
        const username = state.username;
        const account_id = state.account_id;
        const topic_id = state.topic_id;
        API.callSentVoiceAPI(username, account_id, topic_id)
            .then(result => {
                console.log(result); // 顯示結果或進行其他操作
            })
            .catch(error => {
                console.log("呼叫 SentVoiceAPI 時發生錯誤：" + error);
            });
    }

    // 將 loadUserInfo 和 saveFormData 函數掛載到全局 window 對象上
    window.loadUserInfo = loadUserInfo;
    window.saveFormData = saveFormData;
    window.callSentVoiceAPI = callSentVoiceAPI;

    // 在 DOM 內容加載完成後執行初始化操作
    document.addEventListener('DOMContentLoaded', () => {
        // 加載用戶信息
        loadUserInfo();
        callSentVoiceAPI(); // 在頁面加載完成後立即調用 callSentVoiceAPI
        // 為保存按鈕添加事件監聽器
        const saveButton = document.getElementById('save-button');
        if (saveButton) {
            saveButton.addEventListener('click', saveFormData);
        } else {
            console.error("未找到 ID 為 'save-button' 的按鈕");
        }
    });
})();