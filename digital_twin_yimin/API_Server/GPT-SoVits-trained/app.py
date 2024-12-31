from flask import Flask, request, jsonify
import subprocess
import os
import zipfile
import shutil
import json
import requests
import librosa

def user_sovits_state(account_id, sovits_role=None):
    url = "http://104.199.195.184/digital_twin/AI_Expert/end/userVoice_state.php"
    data = {
        "account_id": account_id
    }
    
    # 只有當 sovits_role 不為 None 時才加入 data 字典
    if sovits_role is not None:
        data["sovits_role"] = sovits_role

    response = requests.post(url, data=data)

    if response.status_code == 200:
        print("請求成功")
        print("伺服器回應:", response.text)
        return response.text
    else:
        print("請求失敗，狀態碼:", response.status_code)
        return None

def get_latest_file(directory, extension):
    files = [f for f in os.listdir(directory) if f.endswith(extension)]
    if not files:
        return None
    return max(files, key=lambda f: os.path.getmtime(os.path.join(directory, f)))

app = Flask(__name__)

@app.route('/run_training', methods=['POST'])
def run_training():
    
    username = request.form.get('username')
    if not username:
        return jsonify({"error": "缺少 username 參數"}), 400
    
    account_id = request.form.get('account_id')
    if not account_id:
        return jsonify({"error": "缺少 account_id 參數"}), 400

    # 先檢查用戶狀態
    response_text = user_sovits_state(account_id)
    if response_text == "doing":
        return jsonify({
            "status": "error",
            "message": "目前正在訓練中，請稍後再試"
        }), 400

    # 檢查是否有上傳的檔案
    if 'file' not in request.files:
        return jsonify({"error": "沒有上傳檔案"}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({"error": "沒有選擇檔案"}), 400

    if file and file.filename.endswith('.zip'):
        # 確保 DATA/username/resource 資料夾存在
        resource_folder = os.path.join('DATA', username, 'resource')
        
        # 如果資料夾已存在，先刪除它
        if os.path.exists(resource_folder):
            shutil.rmtree(resource_folder)
        
        # 創建新的資料夾
        os.makedirs(resource_folder, exist_ok=True)

        # 儲存並解壓縮檔案
        zip_path = os.path.join(resource_folder, file.filename)
        file.save(zip_path)
        
        with zipfile.ZipFile(zip_path, 'r') as zip_ref:
            zip_ref.extractall(resource_folder)
        
        # 刪除壓縮檔
        os.remove(zip_path)


    try:
        # 執行 run.py 並傳遞 username 參數
        print("開始訓練...")
        user_sovits_state(account_id, 'doing')
        subprocess.run(['python', 'run.py', username], capture_output=True, text=True)
        print("訓練完成")

        print("開始複製檔案...")

        # 創建新資料夾並複製檔案
        inference_folder = os.path.join('..', 'GPT-SoVITS-Inference', 'trained', username)
        # 如果資料夾已存在，先刪除它
        if os.path.exists(inference_folder):
            shutil.rmtree(inference_folder)
        os.makedirs(inference_folder, exist_ok=True)

        # 複製最新的 .ckpt 檔案
        gpt_source = os.path.join('pretrained_models/gpt_weights/', username)
        latest_ckpt = get_latest_file(gpt_source, '.ckpt')
        if latest_ckpt:
            shutil.copy(os.path.join(gpt_source, latest_ckpt), inference_folder)
            gpt_filename = latest_ckpt
            print(f"已複製最新的 .ckpt 檔案 {latest_ckpt} 到 {inference_folder}")
        else:
            print("未找到 .ckpt 檔案")

        # 複製最新的 .pth 檔案
        sovits_source = os.path.join('pretrained_models/sovits_weights/', username)
        latest_pth = get_latest_file(sovits_source, '.pth')
        if latest_pth:
            shutil.copy(os.path.join(sovits_source, latest_pth), inference_folder)
            sovits_filename = latest_pth
            print(f"已複製最新的 .pth 檔案 {latest_pth} 到 {inference_folder}")
        else:
            print("未找到 .pth 檔案")

        # 修改這部分代碼來選擇合適時長的 WAV 音檔
        slicer_vocals_folder = os.path.join('DATA', username, 'slicer')
        wav_files = [f for f in os.listdir(slicer_vocals_folder) if f.endswith('.wav')]
        
        suitable_file = None
        for wav_file in wav_files:
            wav_path = os.path.join(slicer_vocals_folder, wav_file)
            try:
                # 使用 librosa 讀取音頻檔案
                audio, sr = librosa.load(wav_path, sr=None, duration=10)  # 最多讀取10秒
                duration_seconds = librosa.get_duration(y=audio, sr=sr)
                
                if 3 <= duration_seconds <= 10:
                    suitable_file = wav_file
                    break
            except Exception as e:
                print(f"無法處理檔案 {wav_file}: {str(e)}")
                continue

        if suitable_file:
            source_wav_path = os.path.join(slicer_vocals_folder, suitable_file)
            destination_wav_path = os.path.join(inference_folder, f"{username}.wav")
            
            try:
                # 直接複製 WAV 檔案
                shutil.copy(source_wav_path, destination_wav_path)
                
                print(f"已複製 {suitable_file} 到 {destination_wav_path}")
            except Exception as e:
                print(f"複製音頻檔案時發生錯誤：{str(e)}")
        else:
            print("在 slicer 資料夾中未找到合適時長（3-10秒）的 WAV 檔案")

        # 創建 infer_config.json
        infer_config = {
            "version": "1.0",
            "gpt_path": gpt_filename,
            "sovits_path": sovits_filename,
            "emotion_list": {
                "default": {
                    "ref_wav_path": f"{username}.wav",
                    "prompt_text": "",
                    "prompt_language": "中文"
                }
            }
        }

        try:
            infer_config_path = os.path.join(inference_folder, 'infer_config.json')
            with open(infer_config_path, 'w', encoding='utf-8') as f:
                json.dump(infer_config, f, ensure_ascii=False, indent=4)
            print(f"已成功創建 infer_config.json 於 {infer_config_path}")
        except Exception as e:
            print(f"創建 infer_config.json 時發生錯誤: {str(e)}")
            raise

        user_sovits_state(account_id, username)
        print("所有操作已完成")

        return jsonify({
            "status": "success",
            "message": "訓練完成並已設置推理配置",
        }), 200
    except Exception as e:
        print(f"發生錯誤: {str(e)}")
        return jsonify({
            "status": "error",
            "message": str(e),
        }), 500

if __name__ == '__main__':
    app.run(debug=True, port=5011, host='0.0.0.0')
