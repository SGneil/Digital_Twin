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