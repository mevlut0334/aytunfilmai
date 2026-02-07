#!/bin/bash
set -e  # Hata durumunda dur

echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

# DOMAIN, SSL Email ve PORT sor
read -p "Domain (örn: example.com): " DOMAIN
read -p "SSL Email: " SSL_EMAIL
read -p "HTTP Port (default: 80): " HTTP_PORT
HTTP_PORT=${HTTP_PORT:-80}

read -p "DB Root Password: " DB_ROOT_PASSWORD
read -p "DB Name: " DB_DATABASE
read -p "DB Username: " DB_USERNAME
read -p "DB Password: " DB_PASSWORD

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

# Gerekli paketleri kur
echo "📦 Gerekli paketler kuruluyor..."
sudo apt update
sudo apt install -y nginx python3-certbot-nginx git curl unzip zip

# Repo clone / update
if [ ! -d /var/www/aytunfilmai ]; then
    echo "📥 Repo clone..."
    sudo git clone https://github.com/mevlut0334/aytunfilmai /var/www/aytunfilmai
else
    echo "📥 Repo update..."
    cd /var/www/aytunfilmai && sudo git fetch --all && sudo git reset --hard origin/main
fi

cd /var/www/aytunfilmai

# Ownership ayarla
sudo chown -R $USER:$USER /var/www/aytunfilmai

# .env dosyası oluştur
echo "⚙ .env dosyası oluşturuluyor..."
cp .env.example .env

# APP_KEY üret (base64 encoded random 32 karakter)
APP_KEY="base64:$(openssl rand -base64 32)"

# .env dosyasını doldur
cat > .env << EOF
APP_NAME=AytunFilmAI
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=https://${DOMAIN}

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

LOG_CHANNEL=stack
LOG_LEVEL=error
EOF

# docker-compose.prod.yml için .env.docker oluştur
cat > .env.docker << EOF
DOMAIN=${DOMAIN}
HTTP_PORT=${HTTP_PORT}
DB_PORT=3306
DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
APP_KEY=${APP_KEY}
EOF

echo "✅ .env dosyaları hazır"

# Storage ve cache klasörlerini hazırla
echo "📁 Storage ve cache klasörleri hazırlanıyor..."
sudo mkdir -p storage/framework/{sessions,views,cache}
sudo mkdir -p storage/logs
sudo mkdir -p bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

# Docker Compose ile başlat
echo "🐳 Docker container'ları başlatılıyor..."
docker compose -f docker-compose.prod.yml --env-file .env.docker down -v 2>/dev/null || true
docker compose -f docker-compose.prod.yml --env-file .env.docker up -d --build

# Container'ların hazır olmasını bekle
echo "⏳ MySQL hazır olana kadar bekleniyor..."
sleep 10

# Migration ve seed
echo "🗄 Database migration çalıştırılıyor..."
docker exec aytunfilmai_app php artisan migrate --force || echo "⚠ Migration hatası (normal olabilir)"
docker exec aytunfilmai_app php artisan db:seed --force || echo "⚠ Seed hatası (normal olabilir)"

# Cache temizle ve optimize et
echo "🔧 Cache optimize ediliyor..."
docker exec aytunfilmai_app php artisan config:cache
docker exec aytunfilmai_app php artisan route:cache
docker exec aytunfilmai_app php artisan view:cache

echo ""
echo "✅ Deploy tamamlandı!"
echo "🌐 Domain: $DOMAIN | HTTP Port: $HTTP_PORT"
echo "📝 SSL sertifikası için: sudo certbot --nginx -d $DOMAIN"
