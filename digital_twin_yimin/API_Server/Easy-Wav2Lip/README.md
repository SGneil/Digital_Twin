### 部屬 API_Server 中 Easy-Wav2Lip 專案
```bash
# Easy-Wav2Lip 專案部屬
# 參考網站(https://github.com/anothermartz/Easy-Wav2Lip)
cd API_Server/Easy-Wav2Lip

# 安裝 ffmpeg
sudo apt install ffmpeg

# 建立虛擬環境
conda create --name Easy-Wav2Lip python=3.10 -y
conda activate Easy-Wav2Lip

# 安裝套件
bash install.sh
pip install flask

# 安裝模型
python install.py

# 如果出現錯誤訊息
File "/opt/conda/envs/Easy-Wav2Lip/lib/python3.10/site-packages/basicsr/data/degradations.py", line 8, in <module>
    from torchvision.transforms.functional_tensor import rgb_to_grayscale
ModuleNotFoundError: No module named 'torchvision.transforms.functional_tensor'

修改檔案位置 : /site-packages/basicsr/data/degradations.py
原本內容: from torchvision.transforms.functional_tensor import rgb_to_grayscale
更改內容: from torchvision.transforms._functional_tensor import rgb_to_grayscale

# 設定 GCP 顯示圖片問題
sudo apt-get update
sudo apt-get install xvfb -y
pip uninstall opencv-python -y
pip install opencv-python-headless

GCP不支援顯示圖片，需要依據錯誤訊息將所有opencv顯示圖片的程式碼註解

# 測試放入影片及音檔，設定congig.ini，影片路徑，音檔路徑
python test.py


# 使用 Easy-Wav2Lip 專案
cd API_Server/Easy-Wav2Lip

# 啟用虛擬環境
conda activate Easy-Wav2Lip

# 啟用伺服器
python app.py
```