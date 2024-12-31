from flask import Flask, request, jsonify
import threading
import run

app = Flask(__name__)
def Easy_Wav2Lip_api(user_name, video_name, audio_name):
    try:
        run.set_information(user_name, video_name, audio_name)
    except Exception as e:
        print(f"Error processing {user_name}: {str(e)}")

@app.route('/get_video', methods=['POST'])
def run_Easy_Wav2Lip():
    data = request.json
    user_name = data.get('user_name')
    video_path = data.get('video_path')
    audio_path = data.get('audio_path')

    if not all([user_name, video_path, audio_path]):
        return jsonify({'status': 'error', 'message': 'Missing required parameters'}), 400

    try:
        thread = threading.Thread(target=Easy_Wav2Lip_api, args=(user_name, video_path, audio_path))
        thread.start()
        # print(user_name, video_path, audio_path)
        return jsonify({'status': 'success'})
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5005)
