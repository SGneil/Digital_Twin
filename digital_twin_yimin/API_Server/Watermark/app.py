from flask import Flask, request, jsonify
from flask_cors import CORS  # 导入 CORS 扩展
import run

app = Flask(__name__)
CORS(app)  # 允许所有域名的跨域请求

@app.route('/run_watermark', methods=['POST'])
def run_demo():
    data = request.json
    video_path = data.get('video_path')
    result_path = data.get('result_path')
    logo_path = './logo/logo.png'
    # print(video_path)
    try:
        run.run(video_path, logo_path, result_path)
        return jsonify({'status': 'finish'})
    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5006)
