from flask import Flask, request, jsonify
from flask_cors import CORS
import speech_recognition
import os
from pydub import AudioSegment
import tempfile

app = Flask(__name__)
CORS(app)

@app.route('/stt', methods=['POST'])
def speech_to_text():
    data = request.json
    if 'file_path' not in data:
        print('沒有提供文件路徑')
        return jsonify({'error': '沒有提供文件路徑'}), 400
    
    file_path = data['file_path']
    
    if not os.path.exists(file_path):
        print('文件不存在')
        return jsonify({'error': '文件不存在'}), 400
    
    print('處理文件:', file_path)
    
    try:
        # 使用 pydub 讀取音頻文件
        audio = AudioSegment.from_file(file_path)
        
        # 將音頻轉換為 WAV 格式
        with tempfile.NamedTemporaryFile(suffix=".wav", delete=False) as temp_wav:
            audio.export(temp_wav.name, format="wav")
            temp_wav_path = temp_wav.name

        # 初始化語音識別器
        recognizer = speech_recognition.Recognizer()
        
        # 讀取轉換後的 WAV 文件
        with speech_recognition.AudioFile(temp_wav_path) as source:
            audio_data = recognizer.record(source)
        
        # 使用Google Speech Recognition進行語音識別
        text = recognizer.recognize_google(audio_data, language='zh-TW')
        print(text)
        return jsonify({'result': text})
    
    except speech_recognition.UnknownValueError:
        print("無法識別音頻內容")
        return jsonify({'error': '無法識別音頻內容'}), 400
    except speech_recognition.RequestError as e:
        print(f"語音識別服務請求錯誤: {e}")
        return jsonify({'error': f'語音識別服務請求錯誤: {e}'}), 500
    except Exception as e:
        print(f"處理音頻時發生未知錯誤: {str(e)}")
        return jsonify({'error': f'處理音頻時發生未知錯誤: {str(e)}'}), 500
    finally:
        # 刪除臨時文件
        if 'temp_wav_path' in locals():
            os.remove(temp_wav_path)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5002, debug=True)
