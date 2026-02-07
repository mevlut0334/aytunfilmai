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
APP_NAME="Aytun Film AI"
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://${DOMAIN}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=1440
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"

IYZICO_API_KEY=
IYZICO_SECRET_KEY=
IYZICO_BASE_URL=https://api.iyzipay.com
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
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Nginx konfigürasyonu - önce HTTP için
echo "🌐 Nginx konfigürasyonu oluşturuluyor..."
sudo tee /etc/nginx/sites-available/${DOMAIN} > /dev/null << 'NGINXCONF'
server {
    listen 80;
    server_name DOMAIN_PLACEHOLDER;

    location / {
        proxy_pass http://localhost:HTTP_PORT_PLACEHOLDER;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
NGINXCONF

# Domain ve port'u değiştir
sudo sed -i "s/DOMAIN_PLACEHOLDER/${DOMAIN}/g" /etc/nginx/sites-available/${DOMAIN}
sudo sed -i "s/HTTP_PORT_PLACEHOLDER/${HTTP_PORT}/g" /etc/nginx/sites-available/${DOMAIN}

# Nginx site'ı aktifleştir
sudo ln -sf /etc/nginx/sites-available/${DOMAIN} /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

echo "✅ Nginx yapılandırıldı"

# Docker Compose ile başlat
echo "🐳 Docker container'ları başlatılıyor..."
docker compose -f docker-compose.prod.yml --env-file .env.docker down -v 2>/dev/null || true
docker compose -f docker-compose.prod.yml --env-file .env.docker up -d --build

# Container'ların hazır olmasını bekle
echo "⏳ Container'lar hazırlanıyor..."
sleep 30

# Container'lar ayakta mı kontrol et
if ! docker ps | grep -q aytunfilmai_app; then
    echo "❌ Container'lar başlatılamadı!"
    docker logs aytunfilmai_mysql
    exit 1
fi

echo "✅ Container'lar çalışıyor"

# Storage izinlerini Docker içinde ayarla
echo "📁 Storage izinleri ayarlanıyor..."
docker exec aytunfilmai_app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker exec aytunfilmai_app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Migration ve seed
echo "🗄 Database migration çalıştırılıyor..."
docker exec aytunfilmai_app php artisan migrate --force || echo "⚠ Migration hatası (normal olabilir)"
docker exec aytunfilmai_app php artisan db:seed --force || echo "⚠ Seed hatası (normal olabilir)"

# Cache temizle ve optimize et
echo "🔧 Cache optimize ediliyor..."
docker exec aytunfilmai_app php artisan config:cache
docker exec aytunfilmai_app php artisan route:cache
docker exec aytunfilmai_app php artisan view:cache

# SSL sertifikası al
echo "🔒 SSL sertifikası alınıyor..."
sudo certbot --nginx -d ${DOMAIN} --non-interactive --agree-tos --email ${SSL_EMAIL} --redirect

# SSL otomatik yenileme için cron job ekle
echo "🔄 SSL otomatik yenileme ayarlanıyor..."
(crontab -l 2>/dev/null | grep -v certbot; echo "0 3 * * * certbot renew --quiet --post-hook 'systemctl reload nginx'") | crontab -

echo ""
echo "============================================"
echo "✅ DEPLOYMENT BAŞARIYLA TAMAMLANDI!"
echo "============================================"
echo "🌐 Web Sitesi: https://${DOMAIN}"
echo "🔒 SSL: Aktif (otomatik yenileme ayarlı)"
echo "🐳 Docker: Container'lar çalışıyor"
echo "💾 Database: Hazır"
echo "============================================"
echo ""
echo "📋 Faydalı Komutlar:"
echo "  - Container durumu: docker ps"
echo "  - Logları görüntüle: docker logs aytunfilmai_app"
echo "  - Container'ları durdur: cd /var/www/aytunfilmai && docker compose -f docker-compose.prod.yml down"
echo "  - Container'ları başlat: cd /var/www/aytunfilmai && docker compose -f docker-compose.prod.yml up -d"
echo "============================================"
