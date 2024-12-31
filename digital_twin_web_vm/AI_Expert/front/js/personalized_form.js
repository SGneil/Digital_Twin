(function (global) {
    // 將全局變量集中在一個對象中
    const state = {
        account_id: document.getElementById('account_id').dataset.value,
        username: document.getElementById('username').dataset.value,
        topic_id: 1,
    };


    // 將 API 調用集中
    const API = {
        async getInfo(account_id) {
            const formData = new FormData();
            formData.append('account_id', account_id);
            const response = await fetch('../end/get_info.php', { method: 'POST', body: formData });
            return response.json();
        },
        async conversation_summary(topic_id, account_id) {
            const formData = new FormData();
            formData.append('topic_id', topic_id);
            formData.append('account_id', account_id);
            const response = await fetch('../end/conversation_summary.php', { method: 'POST', body: formData });
            const json = await response.json();
            return json;
        },
        saveUserInfo: (formData) => fetch('../end/save_user_info.php', {
            method: 'POST',
            body: formData
        }),
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

    // 將 API 對象掛載到 global (window) 對象
    global.API = API;

    async function loadUserInfo() {
        console.log("開始加載用戶信息");
        try {
            // 嘗試獲取用戶信息
            const data = await API.getInfo(state.account_id);
            if (data.success && data.data.personal_info) {
                // 如果成功獲取到 personal_info，解析並填充表單
                const personalInfo = JSON.parse(data.data.personal_info);
                fillForm(personalInfo); // 將 personal_info 包裝在一個對象中
            } else {
                // 如果沒有獲取到 personal_info，執行對話摘要
                console.log("沒有用戶資料或 personal_info 為空，執行 conversation_summary");
                const summaryData = await API.conversation_summary(state.topic_id, state.account_id);
                fillForm(summaryData);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("加載用戶信息時發生錯誤，請稍後再試。");
        }
    }

    function fillForm(data) {
        console.log("開始填充表單");
        console.log("收到的數據:", data);

        const fields = ['name', 'gender', 'birthday', 'blood', 'phone', 'address'];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element && data[field]) {
                element.value = data[field];
                console.log(`設置 ${field} 的值為: ${data[field]}`);
            } else if (!element) {
                console.log(`未找到 ID 為 ${field} 的元素`);
            } else {
                console.log(`數據中沒有 ${field} 欄位或欄位為空`);
            }
        });

        console.log("表單填充完成");
    }

    function saveFormData() {
        console.log("開始保存表單數據");
        const formData = new FormData();
        const fields = ['name', 'gender', 'birthday', 'blood', 'phone', 'address'];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                formData.append(field, element.value);
            }
        });

        formData.append('account_id', state.account_id);
        formData.append('topic_id', state.topic_id);

        API.saveUserInfo(formData)
            .then(response => response.json())
            .then(data => {
                console.log("保存結果:", data);
                if (data.success) {
                    alert("資料已成功保存！");
                    generatePrompt();
                    window.location.href = './check_list.php'; // 重定向到 check_list.php
                } else {
                    alert("保存失敗：" + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("保存時發生錯誤，請稍後再試。");
            });
    }

    async function generatePrompt() {
        const formData = new FormData();
        const data = await API.getInfo(state.account_id);
        console.log("data:", data.data);
        formData.append('account_id', state.account_id);
        formData.append('data', JSON.stringify(data.data));
        const response = await API.generatePrompt(formData);
        return response;
    }

    function callSentVoiceAPI() {
        const username = state.username; // 假設我們使用 user 作為用戶名
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

    // 將 loadUserInfo 和 saveFormData 函數暴露到全局作用域
    global.loadUserInfo = loadUserInfo;
    global.saveFormData = saveFormData;
    global.callSentVoiceAPI = callSentVoiceAPI;

})(this);

// 在 DOMContentLoaded 事件中調用 loadUserInfo 和 callSentVoiceAPI
document.addEventListener('DOMContentLoaded', () => {
    loadUserInfo();
    callSentVoiceAPI(); // 在頁面加載完成後立即調用 callSentVoiceAPI

    const saveButton = document.getElementById('save-button');
    if (saveButton) {
        saveButton.addEventListener('click', saveFormData);
    } else {
        console.error("未找到 ID 為 'save-button' 的按鈕");
    }
});
