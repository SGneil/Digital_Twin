import requests
import wave

def save_wave_file(filename, data, channels=1, sampwidth=2, framerate=32000):
    with wave.open(filename, 'wb') as wf:
        wf.setnchannels(channels)
        wf.setsampwidth(sampwidth)
        wf.setframerate(framerate)
        wf.writeframes(b''.join(data))

def send_request(url, chaName, characterEmotion, speakText, save_path):
    requests_json = {
        "character": chaName,
        "emotion": characterEmotion,
        "text": speakText,
        "stream": 'false',
    }

    response = requests.post(url, json=requests_json, stream=True)

    if response.status_code == 200:
        frames = []
        for data in response.iter_content(chunk_size=1024):
            frames.append(data)
        
        # 儲存為 WAV 檔案
        save_wave_file(save_path, frames)
                
        # 刪除暫存的 WAV 檔案
        # os.remove(filename)
        
    else:
        print(f"請求失敗，狀態碼：{response.status_code}")

# if __name__ == '__main__':
#     url = 'http://127.0.0.1:5004/tts'
#     chaName = 'yimin'
#     characterEmotion = 'default'
    
#     while True:
#         chaName = input('輸入角色: ')
#         speakText = input("輸入: ")
#         save_path = chaName + '.wav'
        
#         send_request(url, chaName, characterEmotion, speakText, save_path)