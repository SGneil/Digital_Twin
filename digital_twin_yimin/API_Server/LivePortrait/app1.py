from flask import Flask, request, jsonify
import subprocess
import os

app = Flask(__name__)

@app.route('/run_action_transfer', methods=['POST'])
def run_inference():
    # 获取JSON请求中的数据
    data = request.json
    source_image = data.get('image')
    driving_video = data.get('driving')
    result_folder = data.get('result')

    if not source_image or not driving_video:
        return jsonify({"error": "Missing source_image or driving_video parameter"}), 400

    command = f'python inference.py -s {source_image} -d {driving_video} -o {result_folder}'

    try:
        subprocess.run(command, check=True, shell=True)
        return jsonify({"message": "Inference completed successfully"}), 200
    except subprocess.CalledProcessError as e:
        return jsonify({"error": str(e)}), 500
    
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)