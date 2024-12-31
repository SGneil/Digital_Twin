from flask import Flask, request, jsonify
from flask_cors import CORS  # 引入 CORS 擴展
import gpt
import requests

app = Flask(__name__)
CORS(app)  # 允許所有域名的跨域請求

# GPT 聊天接口
@app.route('/gpt_chat', methods=['POST'])
def chat():
    data = request.json
    messages = data.get('messages', [])  # 使用 get 方法，如果 'messages' 鍵不存在，則返回空列表
    response = gpt.run(messages)
    return response  # 直接返回 AI 的回覆

# 對話摘要接口
@app.route('/conversation_summary', methods=['POST'])
def summary():
    data = request.json
    messages = data.get('messages')  # 使用 get 方法，如果 'messages' 鍵不存在，則返回空列表
    response = gpt.gpt_result(messages)
    return response  # 直接返回 AI 的回覆

# 生成提示詞接口
@app.route('/generate_prompt', methods=['POST'])
def generate_prompt():
    data = request.json
    data = data.get('data')
    
    # 解析數據
    account_id = data.get('account_id')  # 獲取帳號 ID
    user_information_id = data.get('user_information_id')  # 獲取用戶資訊 ID
    information = data.get('information')  # 獲取相關資訊
    
    # 創建回應數據
    prompt, gpt_prompt = gpt.generate_prompt(information)

    # 發送資料到指定的後端服務
    url = "http://104.199.195.184/digital_twin/AI_Expert/end/update_user_prompt.php"
    data = {
        "account_id": account_id,
        "user_information_id": user_information_id,
        "user_prompt": prompt,
        "gpt_prompt": gpt_prompt
    }
    response = requests.post(url, data=data)

    if response.status_code == 200:
        return '', 204  # 返回空內容，HTTP 狀態碼 204 表示成功但無內容
    else:
        return '', 500  # 返回 HTTP 狀態碼 500，表示伺服器錯誤
    

# 親人說話接口
@app.route('/get_gpt_api', methods=['POST'])
def run_demo():
    data = request.json
    msg = data.get('msg')  # 獲取傳入的訊息
    # print(msg)
    try:
        answer = gpt.family_run(msg)  # 調用 GPT 模組進行處理
        print(answer)
        return jsonify({'status': answer})  # 返回結果 JSON 格式
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500  # 返回錯誤資訊，HTTP 狀態碼 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5002, debug=False)  # 啟動伺服器，監聽 0.0.0.0:5002，並關閉 Debug 模式
