### 部屬 API_Server 專案
```bash
# Watermark 專案部屬
cd API_Server/Watermark

# 建立虛擬環境
conda create -n Watermark python==3.9.18 -y
conda activate Watermark

# 安裝套件
pip install moviepy
pip install flask flask_cors


# 使用 Watermark 專案
cd API_Server/Watermark

# 開啟虛擬環境
conda activate Watermark

# 啟用伺服器
python app.py
```