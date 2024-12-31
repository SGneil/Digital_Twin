## 使用說明

### 首次進入GCP，更新環境及安裝git相關套件
```bash
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install git-lfs
sudo git lfs install
sudo apt install tree
```

## 在GCP環境下部屬LAMP
作業系統: debian<br>
版本: debian-11-bullseye-v20240815

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
mysql -u root -p
```

### STEP 3
```bash
# 3.安裝 PHP
sudo apt install php libapache2-mod-php php-mysql -y

# 安裝php-gd編輯圖片套件
sudo apt install php-gd -y


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

## 安裝 https
```bash
# 1.更新系统並安裝必要套件
sudo apt update
sudo apt upgrade -y
sudo apt install apache2 openssl -y

# 2.產生自簽名證書
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ip-selfsigned.key -out /etc/ssl/certs/ip-selfsigned.crt

Country Name (2 letter code): 输入你所在国家的两字母代码，例如 US。
State or Province Name (full name): 输入你所在的州或省。
Locality Name (eg, city): 输入你所在的城市。
Organization Name (eg, company): 输入你的公司或组织名称。
Organizational Unit Name (eg, section): 这个字段可以留空或填写你部门的名称。
Common Name (e.g. server FQDN or YOUR name): 输入你的IP地址 104.199.158.106。
Email Address: 输入你的电子邮件地址。

# 3.設定Apache使用自簽名憑證

# 编辑默认的 SSL 虚拟主机配置文件：
sudo vim /etc/apache2/sites-available/default-ssl.conf

# 确保文件内容如下：
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/ip-selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/ip-selfsigned.key

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
</IfModule>

# 启用 SSL 模块和 SSL 虚拟主机：
sudo a2enmod ssl
sudo a2ensite default-ssl.conf

# 重新启动 Apache 服务器以应用更改：
sudo systemctl restart apache2
```

## 部屬網頁專案
```bash
# clone 專案
git clone https://gitea.evafrp.com/Topic/digital_twin.git

# 切換專案分支到網頁專案
git checkout web-vm

# 建立軟連結將網頁專案資料夾與 /var/www/html/ 連結
# 需要為絕對路徑
sudo ln -s /home/user/digital_twin/ /var/www/html/

# 測試進入網頁
https://your_server_ip/digital_twin/Lobby/front/login_page.php

# 設定 family 資料夾權限
sudo chown -R www-data:www-data family/
sudo chmod -R 777 family/

# 登入 phpmyadmin 匯入資料庫
# 先建立 digital_twin 資料庫
# 匯入 SQL/digital_twin.sql

# 設定檔案傳送失敗問題(調整傳送檔案大小限制)
sudo vim /etc/php/7.4/apache2/php.ini
upload_max_filesize = 100M
post_max_size = 100M
sudo systemctl restart apache2

```

## 完成