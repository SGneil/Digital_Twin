### 部屬 API_Server 中 ChatGPT 專案
```bash
# ChatGPT 專案部屬
cd API_Server/ChatGPT

# 建立虛擬環境
conda create --name ChatGPT python=3.10 -y
conda activate ChatGPT

# 安裝套件
pip install flask openai flask_cors


# 使用 ChatGPT 專案
cd API_Server/ChatGPT

# 啟用虛擬環境
conda activate ChatGPT

# 啟用伺服器
python app.py
```