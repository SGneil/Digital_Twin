## 使用說明

### 要先使用 GCP 中的 SSH 進入啟用 Nvidia driver

### 首次進入 GCP，更新環境及安裝 git 相關套件
```bash
sudo apt-get update -y
sudo apt-get upgrade -y
sudo apt-get install git-lfs -y
sudo git lfs install
sudo apt install tree -y
```

## 在 GCP 環境下部屬 LAMP
作業系統: Deep Learning on Linux<br>
版本: Deep Learning VM with CUDA 12.1 M123<br>
如果要設定固定IP要先設定完成在往下執行

### STEP 1
```bash
# 1.更新系統並安裝 Apache
sudo apt update -y
sudo apt-get upgrade -y
sudo apt install apache2 -y

# 設定開機啟動 Apache2
sudo systemctl enable apache2

# 在瀏覽器輸入 IP，檢驗 Apache 是否運作
http://your_server_ip
```

### STEP 2
```bash
# 2.安裝 MariaDB
sudo apt install mariadb-server -y

# 設定開機啟動 MariaDB
sudo systemctl enable mariadb

# 下面設定全部按n
sudo mysql_secure_installation

# 完成後，輸入以下命令登錄 MariaDB 控制台
sudo mariadb

# 更改密碼
ALTER USER 'root'@'localhost' IDENTIFIED BY '123qwe';
FLUSH PRIVILEGES;

# 離開
EXIT;

# 測試登入 MariaDB
sudo mysql -u root -p
```

### STEP 3
```bash
# 3.安裝 PHP
sudo apt install php libapache2-mod-php php-mysql -y

# 修改 Apache 文件，讓 web server 優先使用 PHP 文件
sudo vim /etc/apache2/mods-enabled/dir.conf

# 編輯內容，把 `index.php` 移至 `DirectoryIndex` 後的第一個位置
原本 : DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
修改 : DirectoryIndex index.php index.cgi index.pl index.html index.xhtml index.htm

# 重新載入 Apache 的配置
sudo systemctl reload apache2
sudo systemctl status apache2
```

### STEP 4
```bash
# 4.測試 PHP 網頁
sudo vim /var/www/html/info.php

# 編輯並輸入下列 PHP 代碼，儲存後離開
<?php
phpinfo();
?>

# 接著測試建立好的 web server 是否正常運作 PHP script
http://your_server_IP_address/info.php

# 刪除測試文件
sudo rm /var/www/html/info.php
```

### STEP 5
```bash
# 5.安裝 phpMyAdmin
phpMyAdmin 的官方網站 [https://www.phpmyadmin.net/](https://www.phpmyadmin.net/)

# 下載 phpMyAdmin (可至官方網站下載最新版本)
wget https://files.phpmyadmin.net/phpMyAdmin/5.0.2/phpMyAdmin-5.0.2-all-languages.zip

# 安裝unzip
sudo apt-get install unzip

# 解壓縮
unzip phpMyAdmin-5.0.2-all-languages.zip

# 將檔案移到伺服器目錄中，並命名為 phpmyadmin
sudo cp -r phpMyAdmin-5.0.2-all-languages /var/www/html/phpmyadmin

# 安裝php-curl
sudo apt-get install php-curl -y
sudo service apache2 restart

# 登入 phpmyadmin
http://your_server_ip/phpmyadmin/
帳號: root
密碼: 123qwe
```

## 部屬專案

### 部屬 WEB_Server 專案
```bash
# clone 專案
git clone https://gitea.evafrp.com/Topic/digital_twin.git

# 切換專案分支到網頁專案
cd digital_twin/
git checkout yimin

# 建立軟連結將網頁專案資料夾與 /var/www/html/ 連結
# 需要為絕對路徑
sudo ln -s /home/user/digital_twin/ /var/www/html/

# 設定 family 資料夾權限
cd WEB_Server/
sudo chown -R www-data:www-data family/
sudo chmod -R 777 family/

# 設定動作遷移 stop.txt 權限
cd WEB_Server/LivePortrait/
sudo chown -R www-data:www-data stop.txt
sudo chmod -R 777 stop.txt

# 登入 phpmyadmin 匯入資料庫
# 先建立 digital_twin 資料庫
# 匯入 SQL/digital_twin.sql

# 設定檔案傳送失敗問題(調整傳送檔案大小限制)
sudo vim /etc/php/7.4/apache2/php.ini
upload_max_filesize = 100M
post_max_size = 100M
sudo systemctl restart apache2
```

### 部屬 API_Server 中 LivePortrait 專案
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
```

### 部屬 API_Server 中 GPT-SoVITS-Inference 專案
```bash
# GPT-SoVITS-Inference 專案部屬
# 參考網站(https://www.yuque.com/xter/zibxlp/nqi871glgxfy717e)
cd API_Server/GPT-SoVITS-Inference

# 安裝 ffmpeg
sudo apt install ffmpeg

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
python run.py
```

# ssh 連線
```bash
ssh -i "C:\Users\YiMin\.ssh\id_rsa" neil47111202@104.155.207.13
```

## 完成