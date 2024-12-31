from flask import Flask, request, jsonify
from flask_cors import CORS  # 导入 CORS 扩展
import run
import os
import logging
import base64

# 設置日誌
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)  # 允许所有域名的跨域请求

@app.route('/get_audio', methods=['POST'])
def run_demo():
    data = request.json
    text = data.get('text')
    print(text)
    sovits_role = data.get('sovits_role')
    save_audio_path = data.get('save_audio_path')
    returnAudio = data.get('returnAudio', False)
    delete_output = True
    
    try:
        # 检查 text 是否为空
        if not text or text.strip() == "":
            return jsonify({'status': 'error', 'message': '文本不能为空'}), 500
        
        url = 'http://localhost:5004/tts'
        characterEmotion = 'default'
        run.send_request(url, sovits_role, characterEmotion, text, save_audio_path)
        
        if returnAudio:
            # 讀取音訊文件並轉換為 base64
            with open(save_audio_path, "rb") as audio_file:
                encoded_string = base64.b64encode(audio_file.read()).decode('utf-8')
            
            # 根据 delete_output 决定是否删除文件
            if delete_output and os.path.exists(save_audio_path):
                os.remove(save_audio_path)
                logger.info(f"已删除文件：{save_audio_path}")
            
            # 返回 base64 编码的音訊數據
            return jsonify({'status': 'OK', 'audio': encoded_string})
        else:
            # 如果不返回音訊，根据 delete_output 决定是否删除文件
            if delete_output and os.path.exists(save_audio_path):
                os.remove(save_audio_path)
                print(f"已删除文件：{save_audio_path}")
            return jsonify({'status': 'OK'})
    except Exception as e:
        # 如果发生错误，根据 delete_output 决定是否删除文件
        if delete_output and os.path.exists(save_audio_path):
            os.remove(save_audio_path)
            print(f"发生错误，已删除文件：{save_audio_path}")
        return jsonify({'status': 'error', 'message': str(e)}), 500

@app.after_request
def after_request(response):
    logger.info("請求處理完成")
    return response

@app.route('/get_family_audio', methods=['POST'])
def family_audio():
    data = request.json
    text = data.get('text')
    sovits_role = data.get('sovits_role')
    save_audio_path = data.get('save_audio_path')
    
    try:
        url = 'http://127.0.0.1:5004/tts'
        characterEmotion = 'default'
        run.send_request(url, sovits_role, characterEmotion, text, save_audio_path)
        # print(text, sovits_role, save_audio_path)
        return jsonify({'status': 'OK'})
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5003, debug=True)
