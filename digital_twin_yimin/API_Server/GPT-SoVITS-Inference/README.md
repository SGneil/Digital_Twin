### 部屬 API_Server 中 GPT-SoVITS-Inference 專案
```bash
# GPT-SoVITS-Inference 專案部屬
# 參考網站(https://www.yuque.com/xter/zibxlp/nqi871glgxfy717e)

# 安裝 ffmpeg
sudo apt install ffmpeg -y

# 建立虛擬環境
conda create -n GPTSoVits python=3.10 -y
conda activate GPTSoVits

# 安裝套件(過程有點久中途記得按y)
bash install.sh

# 安裝模型
bash model.sh
#對於 UVR5（人聲/伴奏分離和混響移除，額外功能），從 UVR5 Weights 下載模型，並將其放置在 tools/uvr5/uvr5_weights 目錄中。

# 設定模型
# 建立 trained 資料夾放入訓練好的模型
 trained
  ├── Rei
  │   ├── Rei.ckpt
  │   ├── Rei.pth
  │   ├── Rei.wav
  │   └── infer_config.json
  ├── YanHua
  │   ├── YanHua.ckpt
  │   ├── YanHua.pth
  │   ├── YanHua.wav
  │   └── infer_config.json
  └── yimin
      ├── YiMin.wav
      ├── infer_config.json
      ├── yimin.ckpt
      └── yimin.pth
      
# 使用 GPT-SoVITS-Inference 專案
cd API_Server/GPT-SoVITS-Inference/

# 開啟虛擬環境
conda activate GPTSoVits

# 啟用伺服器
python pure_api.py
python app.py

```