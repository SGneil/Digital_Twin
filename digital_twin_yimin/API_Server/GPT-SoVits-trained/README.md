### 部屬 API_Server 中 GPT-SoVits-trained 專案
```bash
# GPT-SoVits-trained 專案部屬
# 參考網址(https://github.com/ZaVang/GPT-SoVITS)
cd API_Server/GPT-SoVits-trained

# 建立虛擬環境
conda create -n GPTSoVits-train python=3.9 -y
conda activate GPTSoVits-train

# 安裝套件(過程有點久中途記得按y)
bash install.sh

# 安裝模型
bash model.sh
#對於 UVR5（人聲/伴奏分離和混響移除，額外功能），從 UVR5 Weights 下載模型，並將其放置在 tools/uvr5/uvr5_weights 目錄中。


# 使用 GPT-SoVits-trained 專案
cd API_Server/GPT-SoVits-trained

# 啟用虛擬環境
conda activate GPTSoVits-train

# 建立 DATA 資料夾
DATA/
└── <角色名稱>
    └── resource
        ├── 1.mp3
        ├── 2.mp3
        ├── 3.mp3
        └── 4.mp3
# 修改 run.py 角色名稱
python app.py
```