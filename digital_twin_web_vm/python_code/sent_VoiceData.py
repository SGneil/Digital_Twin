from flask import Flask, request, jsonify
import os
import zipfile
import requests
from werkzeug.utils import secure_filename
from threading import Thread
from queue import Queue

app = Flask(__name__)
# 創建一個全局佇列
request_queue = Queue()
# 控制佇列處理的標誌
is_processing = False

# 替代方案：使用本地路徑
PHP_PATH = os.path.join("digital_twin", "AI_Expert", "end", "VoiceCloneRequests_status.php")

def process_queue():
    """處理佇列中的請求"""
    global is_processing
    
    while True:
        if request_queue.empty():
            print("\n佇列處理完成，執行緒結束")
            print_queue_status()
            is_processing = False
            break
            
        request_data = request_queue.get()
        username = request_data['username']
        account_id = request_data['account_id']
        topic_id = request_data['topic_id']
        API_url = request_data['API_url']
        input_dir = request_data['input_dir']

        print(f"\n開始處理請求 - Account ID: {account_id}")
        print_queue_status()

        # 創建臨時 ZIP 文件
        zip_filename = f'{secure_filename(username)}_audio.zip'
        zip_path = f'/tmp/{zip_filename}'

        try:
            with zipfile.ZipFile(zip_path, 'w') as zipf:
                for root, dirs, files in os.walk(input_dir):
                    for file in files:
                        file_path = os.path.join(root, file)
                        arcname = os.path.relpath(file_path, input_dir)
                        zipf.write(file_path, arcname)
            
            # 更新請求狀態
            if update_request_status(account_id, topic_id):
                # 發送 ZIP 檔案並等待回應
                try:
                    files = {'file': (os.path.basename(zip_path), open(zip_path, 'rb'))}
                    data = {'username': username, 'account_id': account_id}
                    response = requests.post(API_url, files=files, data=data)
                    
                    if response.status_code == 200:
                        print(f"任務完成 - Account ID: {account_id}, Topic ID: {topic_id}")
                        print_queue_status()
                    else:
                        print(f"請求處理失敗: {username}, {topic_id}, 狀態碼: {response.status_code}")
                except requests.RequestException as e:
                    print(f"傳送 ZIP 檔案失敗: {str(e)}")
                finally:
                    # 清理臨時 ZIP 文件
                    if os.path.exists(zip_path):
                        os.remove(zip_path)
        except Exception as e:
            print(f"創建或處 ZIP 檔案時發生錯誤: {str(e)}")
        
        request_queue.task_done()

def send_zip_file(zip_path, API_url, username, account_id):
    files = {'file': (os.path.basename(zip_path), open(zip_path, 'rb'))}
    data = {'username': username, 'account_id': account_id}
    
    try:
        requests.post(API_url, files=files, data=data)
    except requests.RequestException as e:
        print(f"傳送 ZIP 檔案失敗: {str(e)}")
    finally:
        # 清理臨時 ZIP 文件
        os.remove(zip_path)

def check_request_status(account_id, topic_id):
    """檢查語音複製請求的狀態"""
    try:
        form_data = {
            'account_id': account_id,
            'topic_id': topic_id,
            'action': 'check'
        }
        # 修改 URL 路徑
        url = "http://localhost/digital_twin/AI_Expert/end/VoiceCloneRequests_status.php"
        
        response = requests.post(url, data=form_data)
        
        if response.status_code == 200:
            return response.text.strip() == 'true'
        print(f"檢查請求狀態失敗: 狀態碼 {response.status_code}")
        return None
        
    except requests.RequestException as e:
        print(f"檢查請求狀態失敗: {str(e)}")
        return None

def update_request_status(account_id, topic_id):
    """更新語音複製請求的狀態"""
    try:
        form_data = {
            'account_id': account_id,
            'topic_id': topic_id,
            'action': 'update',
            'status': 'true'
        }
        # 修改 URL 路徑
        url = "http://localhost/digital_twin/AI_Expert/end/VoiceCloneRequests_status.php"

        response = requests.post(url, data=form_data)
        
        success = response.text.strip() == 'success'
        if not success:
            print(f"更新請求狀態失敗: 回應為 {response.text}")
        return success
    except requests.RequestException as e:
        print(f"更新請求狀態失敗: {str(e)}")
        return False

def print_queue_status():
    """打印目前佇列中的所有請求"""
    temp_queue = Queue()
    queue_items = []
    
    # 取出所有項目並記錄
    while not request_queue.empty():
        item = request_queue.get()
        queue_items.append(item)
        temp_queue.put(item)
    
    # 將項目放回佇列
    while not temp_queue.empty():
        request_queue.put(temp_queue.get())
    
    print("\n=== 目前佇列狀態 ===")
    if not queue_items:
        print("佇列為空")
    else:
        for idx, item in enumerate(queue_items, 1):
            print(f"請求 {idx}:")
            print(f"  使用者: {item['username']}")
            print(f"  Account ID: {item['account_id']}")
            print(f"  Topic ID: {item['topic_id']}")
    print("==================\n")

@app.route('/compress_and_send', methods=['POST'])
def compress_and_send():
    global is_processing
    
    username = request.form.get('username')
    account_id = request.form.get('account_id')
    topic_id = request.form.get('topic_id')
    API_url = request.form.get('API_url')
    
    if not all([username, API_url, account_id, topic_id]):
        print("錯誤: 缺少必要參數")
        return jsonify({"error": "缺少必要參數"}), 400

    try:
        # 檢查請求狀態
        status_response = check_request_status(account_id, topic_id)
        if status_response is None:  # 如果沒有得到有效回應
            print(f"錯誤: 無法檢查請求狀態 - Account ID: {account_id}, Topic ID: {topic_id}")
            return jsonify({"error": "無法檢查請求狀態"}), 500
            
        if status_response:  # 如果狀態為 true
            print(f"請求已存在 - Account ID: {account_id}, Topic ID: {topic_id}")
            return jsonify({"message": "請求在處理中或處理完畢"}), 200
    
        print(f"新任務加入佇列 - Account ID: {account_id}, Topic ID: {topic_id}")
        
        # 確認狀態更新成功
        if not update_request_status(account_id, topic_id):
            print(f"錯誤: 無法更新請求狀態 - Account ID: {account_id}, Topic ID: {topic_id}")
            return jsonify({"error": "無法更新請求狀態"}), 500

        # 設定目錄路徑
        input_dir = f'../family/{username}/input/audio'
        
        # 檢查目錄是否存在
        if not os.path.exists(input_dir):
            print(f"錯誤: 找不到輸入目錄 - {input_dir}")
            return jsonify({"error": "找不到輸入目錄"}), 404

        # 創建新的請求數據
        new_request = {
            'username': username,
            'account_id': account_id,
            'topic_id': topic_id,
            'API_url': API_url,
            'input_dir': input_dir
        }

        # 移除相同 account_id 的舊請求
        temp_queue = Queue()
        while not request_queue.empty():
            item = request_queue.get()
            if item['account_id'] != account_id:
                temp_queue.put(item)
            else:
                print(f"移除舊的請求 - Account ID: {account_id}")

        # 將保留的請求放回原始佇列
        while not temp_queue.empty():
            request_queue.put(temp_queue.get())

        # 加入新請求
        request_queue.put(new_request)
        print(f"\n新增請求 - Account ID: {account_id}")
        print_queue_status()

        # 如果沒有處理執行緒在運行，啟動一個新的
        if not is_processing:
            is_processing = True
            Thread(target=process_queue).start()

        return jsonify({"message": "請求已加入佇列"}), 200
        
    except Exception as e:
        print(f"處理請求時發生錯誤: {str(e)}")
        return jsonify({"error": f"處理請求時發生錯誤"}), 500

if __name__ == '__main__':
    app.run(debug=True)