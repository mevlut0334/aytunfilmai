#!/bin/bash
echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

# DOMAIN, SSL Email ve PORT sor
read -p "Domain (örn: example.com): " DOMAIN
read -p "SSL Email: " SSL_EMAIL
read -p "HTTP Port (örn: 80): " HTTP_PORT
read -p "DB Root Password: " DB_ROOT_PASSWORD
read -p "DB Name: " DB_DATABASE
read -p "DB Username: " DB_USERNAME
read -p "DB Password: " DB_PASSWORD

# Script izinleri
chmod +x ~/deploy.sh

# RAM ve swap kontrolü
TOTAL_RAM=$(free -m | awk '/^Mem:/{print $2}')
echo "💾 Toplam RAM: ${TOTAL_RAM}MB"

if [ "$TOTAL_RAM" -lt 2000 ]; then
    if [ ! -f /swapfile ]; then
        echo "⚠ RAM düşük, 2GB swap oluşturuluyor..."
        sudo fallocate -l 2G /swapfile
        sudo chmod 600 /swapfile
        sudo mkswap /swapfile
        sudo swapon /swapfile
        echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
    else
        echo "✔ Swap zaten mevcut"
    fi
fi

# Repo clone / update
if [ ! -d /var/www/aytunfilmai ]; then
    echo "📥 Repo clone..."
    sudo git clone https://github.com/mevlut0334/aytunfilmai /var/www/aytunfilmai
else
    echo "📥 Repo update..."
    cd /var/www/aytunfilmai && sudo git fetch --all && sudo git reset --hard origin/main
fi

# ENV dosyası ve APP_KEY
cd /var/www/aytunfilmai
if [ ! -f .env ]; then
    echo "⚙ ENV oluşturuluyor ve APP_KEY üretiliyor..."
    cp .env.example .env
    php artisan key:generate
else
    echo "✔ ENV dosyası zaten mevcut, APP_KEY korunuyor"
fi

# ENV değişkenlerini güncelle
sed -i "s|APP_KEY=.*|APP_KEY=$(php artisan key:generate --show)|" .env
sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
sed -i "s|DB_HOST=.*|DB_HOST=mysql|" .env
sed -i "s|DB_PORT=.*|DB_PORT=${DB_PORT:-3306}|" .env
sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env

# Paketler
sudo apt update
sudo apt install -y nginx python3-certbot-nginx

# Docker Compose ile containerları ayağa kaldır
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml up -d --build

echo "✅ Deploy tamamlandı!"
echo "🌐 Domain: $DOMAIN | HTTP Port: $HTTP_PORT"
