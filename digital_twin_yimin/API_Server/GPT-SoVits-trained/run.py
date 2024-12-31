import os
import sys
from tools.uvr5 import use_uvr5
from tools import use_slice_audio
import check_time
from tools.asr import funasr_asr
from src.preprocess import use_process
import subprocess
import tempfile

# 在文件開頭添加這行
os.environ['TEMP'] = tempfile.gettempdir()

# 設定參數
if len(sys.argv) < 2:
    print("請提供 username 參數")
    sys.exit(1)

username = sys.argv[1]

# 當前目錄
current_directory = os.getcwd()

# 使用 uvr5

# 替換成你要使用的模型名稱
model_name = "HP2_all_vocals"  
# 替換成輸入音頻文件夾路徑
inp_root = current_directory + "/DATA/"+ username +"/resource"  
# 替換成主人聲輸出文件夾路徑
save_root_vocal = current_directory + "/DATA/"+ username +"/uvr5_vocals"  
# 替換成非主人聲輸出文件夾路徑
save_root_ins = current_directory + "/DATA/"+ username +"/uvr5_others"  
# 人聲提取激進程度
agg = 10  
# 導出文件格式
format0 = "flac"  
# 如果不使用文件夾，可以在這裡加入單獨的音頻文件路徑
paths = []  

use_uvr5.uvr(model_name, inp_root, save_root_vocal, paths, save_root_ins, agg, format0)

# 音頻分割長度

# 替換成主人聲輸出文件夾路徑
input_path = current_directory + "/DATA/"+ username +"/uvr5_vocals"
# 替換成分割音檔輸出文件夾路徑
output_path = current_directory + "/DATA/"+ username +"/slicer"
# 音量小於這個值視作靜音的備選切割點
db_threshold = -25
# 每段最小多長，如果第一段太短一直和後面段連起來直到超過這個值
min_length = 4000
# 最短切割間隔
min_interval = 300
# 怎麼算音量曲線，越小精度越大計算量越高（不是精度越大效果越好）
hop_size = 10
# 切完後靜音最多多長
max_sil_kept = 500
# 歸一化後最大值多少
max_amp = 0.9
# 混多少比例歸一化後音訊進來
alpha = 0.25

use_slice_audio.slice(input_path, output_path, db_threshold, min_length, min_interval, hop_size,max_sil_kept,  max_amp, alpha)

# 檢查音頻長度是否小於20秒

folder_path = current_directory + "/DATA/"+ username +"/slicer"
check_time.process_audio_files(folder_path)

# ASR 標記
input_folder = current_directory + "/DATA/"+ username +"/slicer"
output_folder = current_directory + "/DATA/"+ username +"/asr_opt"
model_size = "large"
language = "zh"

funasr_asr.execute_asr(input_folder, output_folder, model_size, language)

# 一鍵三連
input_txt_path = current_directory + "/DATA/"+ username +"/asr_opt/slicer.list"
save_path = current_directory + "/logs/"+ username
input_wav_path = current_directory + "/DATA/"+ username +"/slicer"

use_process.main(input_txt_path, save_path, input_wav_path)

# 訓練(1)train sovits model
# 定義要執行的指令和參數
command = [
    'python', 'src/train/train_sovits.py',
    '-c', 'src/configs/sovits.json',
    '-n', username,
    '-t', 'sovits',
    '-e', '8',
    '-lr', '0.4',
    '-bs', '4',
    '-nw', '0',
    '--save_every_epoch', '4',
    '--keep_ckpts', '4'
]

# 使用 subprocess.run 執行指令
result = subprocess.run(command, capture_output=True, text=True)

# 輸出執行結果
print("標準輸出:")
print(result.stdout)
print("標準錯誤:")
print(result.stderr)

# 訓練(2)train gpt mpdel
# 定義要執行的指令和參數
command = [
    'python', 'src/train/train_gpt.py',
    '-c', 'src/configs/s1longer.yaml',
    '-n', username,
    '-e', '15',
    '-bs', '4',
    '-nw', '0',
    '--save_every_epoch', '5'
]

# 使用 subprocess.run 執行指令
result = subprocess.run(command, capture_output=True, text=True)

# 輸出執行結果
print("標準輸出:")
print(result.stdout)
print("標準錯誤:")
print(result.stderr)