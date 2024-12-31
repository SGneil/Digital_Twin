### 部屬 API_Server 專案
```bash
# LivePortrait 專案部屬
cd API_Server/LivePortrait

# 建立虛擬環境
conda create -n LivePortrait python==3.9.18 -y
conda activate LivePortrait

# 安裝套件
bash install.sh

# 測試 CUDA 安裝是否成功
python cuda_test.py


# 使用 LivePortrait 專案
cd API_Server/LivePortrait

# 開啟虛擬環境
conda activate LivePortrait

# 啟用伺服器
python app1.py


# 如果執行過程中出現warning訊息
# 參考網址(https://onnxruntime.ai/docs/install/)
pip install onnxruntime

pip install onnxruntime-gpu

pip install onnxruntime-gpu --extra-index-url https://aiinfra.pkgs.visualstudio.com/PublicPackages/_packaging/onnxruntime-cuda-12/pypi/simple/

# ONNX is built into PyTorch
pip install torch

# tensorflow
pip install tf2onnx

# sklearn
pip install skl2onnx

# ImportError: cannot import name 'preserve_channel_dim' from 'albucore.utils' (/home/m416-3090/anaconda3/envs/LivePortrait/lib/python3.9/site-packages/albucore/utils.py)
pip install --upgrade albumentations albucore
```