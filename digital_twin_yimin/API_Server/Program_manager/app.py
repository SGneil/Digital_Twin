from flask import Flask, render_template, jsonify
import subprocess
import os
import signal
import threading
import queue
import requests
import psutil
import time

app = Flask(__name__)

# 儲存所有運行中的程式
running_processes = {}

# 程式配置
PROGRAMS = {
    'chatgpt': {
        'name': 'ChatGPT',
        'path': '../ChatGPT',
        'env': 'ChatGPT',
        'command': 'python app.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'wav2lip': {
        'name': 'Easy-Wav2Lip',
        'path': '../Easy-Wav2Lip',
        'env': 'Easy-Wav2Lip',
        'command': 'python app.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'liveportrait': {
        'name': 'LivePortrait',
        'path': '../LivePortrait',
        'env': 'LivePortrait',
        'command': 'python app1.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'watermark': {
        'name': 'Watermark',
        'path': '../Watermark',
        'env': 'Watermark',
        'command': 'python app.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'GPT-SoVITS-Inference_pure-api': {
        'name': 'GPT-SoVITS-Inference_pure-api',
        'path': '../GPT-SoVITS-Inference',
        'env': 'GPTSoVits',
        'command': 'python pure_api.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'GPT-SoVITS-Inference_api': {
        'name': 'GPT-SoVITS-Inference_api',
        'path': '../GPT-SoVITS-Inference',
        'env': 'GPTSoVits',
        'command': 'python app.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    },
    'GPT-SoVITS-Trained': {
        'name': 'GPT-SoVits-trained',
        'path': '../GPT-SoVits-trained',
        'env': 'GPTSoVits-train',
        'command': 'python app.py',
        'status': 'stopped',
        'output': queue.Queue(),
        'output_history': []
    }
}

def update_server_state(server_name, state):
    """
    更新伺服器狀態
    server_name: 伺服器名稱
    state: 伺服器狀態 ("已開啟" 或 "未開啟")
    """
    url = "http://104.199.195.184/digital_twin/Admin/end/update_server_state.php"
    data = {
        "server_name": server_name,
        "state": state
    }
    try:
        response = requests.post(url, data=data)
        if response.text.strip() == 'Success':
            print(f"[Debug] 成功更新伺服器 {server_name} 狀態為 {state}")
        else:
            print(f"[Error] 更新伺服器狀態失敗: {response.text}")
    except Exception as e:
        print(f"[Error] 發送請求時發生錯誤: {str(e)}")

def run_program(program_id):
    program = PROGRAMS[program_id]
    
    # 在啟動程式前清空輸出隊列
    while not program['output'].empty():
        program['output'].get()

    program['output_history'] = []

    # 添加啟動訊息
    program['output'].put(f"正在啟動 {program['name']}...")
    
    # 獲取程式的絕對路徑
    abs_path = os.path.abspath(program['path'])
    
    # 獲取 conda 的完整路徑
    conda_path = os.path.expanduser('~/anaconda3')
    
    # 修改命令執行方式
    cmd = f'source {conda_path}/etc/profile.d/conda.sh && conda activate {program["env"]} && cd "{abs_path}" && {program["command"]}'
    
    # 修改 Popen 的參數
    process = subprocess.Popen(
        cmd,
        shell=True,
        executable='/bin/bash',
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,
        text=True,
        env={
            **os.environ.copy(), 
            'PYTHONUNBUFFERED': '1',
        },
        preexec_fn=os.setsid,
        bufsize=1,
        universal_newlines=True
    )
    
    running_processes[program_id] = process
    program['status'] = 'running'
    
    # 更新伺服器狀態為開啟
    update_server_state(program['name'], "已開啟")
    
    # 修改輸出讀取函數
    def output_reader(pipe, queue, program):
        try:
            while True:
                line = pipe.readline()
                if not line:
                    break
                    
                line = line.rstrip('\n\r')
                if line.strip():  # 只處理非空行
                    print(f"[Debug][{program_id}] 讀取到輸出: {line}")
                    queue.put(line)
                    program['output_history'].append(line)
                
        except Exception as e:
            error_msg = f"輸出讀取錯誤: {str(e)}"
            print(f"[Error][{program_id}] {error_msg}")
            queue.put(error_msg)
            program['output_history'].append(error_msg)
        finally:
            print(f"[Debug][{program_id}] 輸出讀取器結束")
            queue.put("程式已結束運行")
            program['output_history'].append("程式已結束運行")
            pipe.close()
    
    # 創建並啟動輸出讀取線程
    output_thread = threading.Thread(
        target=output_reader,
        args=(process.stdout, program['output'], program),
        daemon=True
    )
    output_thread.start()
    
    # 添加進程監控
    def monitor():
        process.wait()
        print(f"[Debug][{program_id}] 進程結束，返回碼: {process.returncode}")
        program['status'] = 'stopped'
        if program_id in running_processes:
            del running_processes[program_id]
    
    monitor_thread = threading.Thread(target=monitor, daemon=True)
    monitor_thread.start()

def initialize_server_states():
    """
    初始化所有伺服器狀態為未開啟
    """
    # 更新所有伺服器狀態
    for program in PROGRAMS.values():
        update_server_state(program['name'], "未開啟")
    
    # 重設 LivePortrait 的 stop.txt
    try:
        stop_file_path = '../../WEB_Server/LivePortrait/stop.txt'
        with open(os.path.abspath(stop_file_path), 'w') as f:
            f.write('0')
        print("[Info] 成功重設 LivePortrait stop.txt")
    except Exception as e:
        print(f"[Error] 重設 LivePortrait stop.txt 時發生錯誤: {str(e)}")

def kill_process_on_ports():
    """
    檢查並結束指定 port 上運行的程式
    """
    target_ports = [5001, 5002, 5003, 5004, 5005, 5006, 5011]
    
    for proc in psutil.process_iter(['pid', 'name']):
        try:
            connections = proc.net_connections()
            for conn in connections:
                if conn.status == 'LISTEN' and conn.laddr.port in target_ports:
                    print(f"[Info] 發現 port {conn.laddr.port} 被程式 {proc.pid} 占用，正在結束該程式...")
                    try:
                        # 嘗試正常結束程式
                        parent = psutil.Process(proc.pid)
                        for child in parent.children(recursive=True):
                            child.terminate()
                        parent.terminate()
                        
                        # 等待程式結束
                        time.sleep(1)
                        
                        # 如果程式還在運行，強制結束
                        if parent.is_running():
                            for child in parent.children(recursive=True):
                                child.kill()
                            parent.kill()
                            
                        print(f"[Info] 成功結束在 port {conn.laddr.port} 的程式")
                    except Exception as e:
                        print(f"[Error] 結束程式時發生錯誤: {str(e)}")
        except (psutil.NoSuchProcess, psutil.AccessDenied, psutil.ZombieProcess):
            continue

@app.route('/start/<program_id>')
def start_program(program_id):
    if program_id not in running_processes:
        thread = threading.Thread(target=run_program, args=(program_id,))
        thread.start()
        return jsonify({'status': 'started'})
    return jsonify({'status': 'already running'})

@app.route('/stop/<program_id>')
def stop_program(program_id):
    if program_id in running_processes:
        process = running_processes[program_id]
        try:
            # 發送 SIGTERM 信號到整個進程組
            os.killpg(os.getpgid(process.pid), signal.SIGTERM)
            
            # 等待進程結束
            try:
                process.wait(timeout=5)
            except subprocess.TimeoutExpired:
                # 如果進程沒有及時結束，強制結束
                os.killpg(os.getpgid(process.pid), signal.SIGKILL)
            
            # 清理資源
            if program_id in running_processes:
                del running_processes[program_id]
            PROGRAMS[program_id]['status'] = 'stopped'
            stop_message = "程式已停止"
            PROGRAMS[program_id]['output'].put(stop_message)
            PROGRAMS[program_id]['output_history'].append(stop_message)
            
            # 更新伺服器狀態為未開啟
            update_server_state(PROGRAMS[program_id]['name'], "未開啟")
            
            return jsonify({'status': 'stopped'})
        except Exception as e:
            return jsonify({'status': f'error: {str(e)}'})
    return jsonify({'status': 'not running'})

@app.route('/status/<program_id>')
def get_status(program_id):
    program = PROGRAMS[program_id]
    new_output = []
    
    # 從隊列中獲取所有新輸出
    try:
        while True:
            line = program['output'].get(timeout=0.1)
            if line:
                new_output.append(line)
    except queue.Empty:
        pass
    
    return jsonify({
        'status': program['status'],
        'output': program['output_history'],
        'new_output': new_output
    })

@app.route('/clear_output/<program_id>')
def clear_output(program_id):
    if program_id in PROGRAMS:
        PROGRAMS[program_id]['output_history'] = []
        # 清空 Queue
        while not PROGRAMS[program_id]['output'].empty():
            PROGRAMS[program_id]['output'].get()
        return jsonify({'status': 'cleared'})
    return jsonify({'status': 'program not found'})

if __name__ == '__main__':
    # 結束指定 port 上的程式
    kill_process_on_ports()
    
    # 初始化所有伺服器狀態
    initialize_server_states()
    
    # 設定 Flask 不要輸出請求日誌
    app.run(host='0.0.0.0', port=5010, debug=False)