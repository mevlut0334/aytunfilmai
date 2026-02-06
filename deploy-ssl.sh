#!/bin/bash

# Renkler
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}=========================================${NC}"
echo -e "${BLUE}  Aytun Film AI Docker + SSL Deployment ${NC}"
echo -e "${BLUE}=========================================${NC}"
echo ""

# Müşteri bilgilerini al
read -p "Müşteri adı (örn: aytunfilmai): " CLIENT_NAME
read -p "Domain adı (örn: example.com): " DOMAIN
read -p "SSL için email adresi: " SSL_EMAIL
read -p "MySQL Port (varsayılan 3306): " DB_PORT
DB_PORT=${DB_PORT:-3306}

echo ""

# .env.production dosyası zaten var mı kontrol et
if [ -f ".env.production" ]; then
    echo -e "${YELLOW}⚠ .env.production dosyası bulundu!${NC}"
    echo -e "${YELLOW}Mevcut ayarlar kullanılacak.${NC}"

    # Mevcut değerleri oku
    DB_PASSWORD=$(grep "^DB_PASSWORD=" .env.production | cut -d'=' -f2)
    DB_ROOT_PASSWORD=$(grep "^DB_ROOT_PASSWORD=" .env.production | cut -d'=' -f2)
    APP_KEY=$(grep "^APP_KEY=" .env.production | cut -d'=' -f2)

    echo -e "${GREEN}✓ Mevcut şifreler kullanılıyor${NC}"
else
    echo -e "${YELLOW}Yeni şifreler oluşturuluyor...${NC}"

    # Güvenli şifreler oluştur
    DB_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
    DB_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
    APP_KEY=""

    echo -e "${GREEN}✓ Yeni şifreler oluşturuldu${NC}"
fi

# .env.production dosyasını oluştur/güncelle
echo -e "${BLUE}[1/14] .env dosyası oluşturuluyor...${NC}"
cat > .env.production << EOF
CLIENT_NAME=${CLIENT_NAME}
HTTP_PORT=80
HTTPS_PORT=443
DB_PORT=${DB_PORT}

APP_NAME="Aytun Film AI"
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=https://${DOMAIN}

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=${CLIENT_NAME}_db
DB_USERNAME=${CLIENT_NAME}_user
DB_PASSWORD=${DB_PASSWORD}
DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
EOF

echo -e "${GREEN}✓ .env dosyası oluşturuldu${NC}"

# Docker container'ları başlat
echo -e "${BLUE}[2/14] Docker container'ları başlatılıyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ Docker container'ları başlatılamadı!${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Container'lar başlatıldı${NC}"

# MySQL hazır olmasını bekle
echo -e "${BLUE}[3/14] MySQL hazır olması bekleniyor...${NC}"
echo -e "${YELLOW}Docker healthcheck ile MySQL kontrol ediliyor...${NC}"

for i in {1..40}; do
    HEALTH_STATUS=$(docker inspect --format='{{.State.Health.Status}}' ${CLIENT_NAME}_mysql 2>/dev/null)
    if [ "$HEALTH_STATUS" = "healthy" ]; then
        echo -e "${GREEN}✓ MySQL hazır${NC}"
        break
    fi
    if [ $i -eq 40 ]; then
        echo -e "${RED}✗ MySQL başlamadı (timeout)${NC}"
        docker compose -f docker-compose.prod.yml --env-file .env.production logs mysql --tail=20
        exit 1
    fi
    echo "MySQL healthcheck bekleniyor... ($i/40) - Durum: ${HEALTH_STATUS:-starting}"
    sleep 3
done

sleep 5

# MySQL user ve database oluştur
echo -e "${BLUE}[4/14] MySQL user ve database oluşturuluyor...${NC}"

docker exec ${CLIENT_NAME}_mysql mysql -u root -p"${DB_ROOT_PASSWORD}" -e "
CREATE DATABASE IF NOT EXISTS ${CLIENT_NAME}_db;
CREATE USER IF NOT EXISTS '${CLIENT_NAME}_user'@'%' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${CLIENT_NAME}_db.* TO '${CLIENT_NAME}_user'@'%';
FLUSH PRIVILEGES;
" 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ MySQL user ve database hazır${NC}"
else
    echo -e "${RED}✗ MySQL user/database oluşturulamadı!${NC}"
    echo -e "${YELLOW}MySQL logları:${NC}"
    docker compose -f docker-compose.prod.yml --env-file .env.production logs mysql --tail=30
    exit 1
fi

# Storage dizin yapısını oluştur
echo -e "${BLUE}[5/14] Storage dizin yapısı oluşturuluyor...${NC}"

docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app bash -c "
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,testing,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
touch storage/logs/laravel.log
"

echo -e "${GREEN}✓ Storage dizinleri oluşturuldu${NC}"

# İzinleri düzelt
echo -e "${BLUE}[6/14] Dizin izinleri ayarlanıyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app bash -c "
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache
"
echo -e "${GREEN}✓ İzinler ayarlandı${NC}"

# APP_KEY oluştur (eğer yoksa)
echo -e "${BLUE}[7/14] APP_KEY kontrol ediliyor...${NC}"

if [ -z "$APP_KEY" ]; then
    echo -e "${YELLOW}APP_KEY oluşturuluyor...${NC}"

    docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan key:generate --force

    sleep 2
    APP_KEY=$(docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app cat /var/www/html/.env | grep "^APP_KEY=" | cut -d'=' -f2 | tr -d '\r\n')

    if [ -z "$APP_KEY" ]; then
        APP_KEY="base64:$(openssl rand -base64 32)"
    fi

    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env.production

    echo -e "${GREEN}✓ APP_KEY oluşturuldu: ${APP_KEY}${NC}"
else
    echo -e "${GREEN}✓ Mevcut APP_KEY kullanılıyor${NC}"
fi

# .env izinlerini düzelt
echo -e "${BLUE}[8/14] .env izinleri ayarlanıyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app bash -c "
chown www-data:www-data /var/www/html/.env
chmod 644 /var/www/html/.env
"
echo -e "${GREEN}✓ .env izinleri ayarlandı${NC}"

# Cache temizle
echo -e "${BLUE}[9/14] Cache temizleniyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan config:clear
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan cache:clear
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan view:clear

# Migration çalıştır
echo -e "${BLUE}[10/14] Veritabanı migration'ları çalıştırılıyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan migrate --force

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠ Migration hatası oldu${NC}"
fi

# Seeder çalıştır
echo -e "${BLUE}[11/14] Seeder'lar çalıştırılıyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan db:seed --force

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠ Seeder hatası oldu${NC}"
fi

# Storage link
echo -e "${BLUE}[12/14] Storage link oluşturuluyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan storage:link

# Optimize
echo -e "${BLUE}[13/14] Uygulama optimize ediliyor...${NC}"
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan view:cache

echo -e "${GREEN}✓ Optimizasyon tamamlandı${NC}"

# SSL sertifikası
echo -e "${BLUE}[14/14] SSL sertifikası kontrol ediliyor...${NC}"

if ! command -v certbot &> /dev/null; then
    echo -e "${YELLOW}Certbot kuruluyor...${NC}"
    sudo apt update
    sudo apt install certbot -y
fi

if [ ! -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    sudo certbot certonly --webroot \
      --webroot-path=/var/www/${CLIENT_NAME}/public \
      --email ${SSL_EMAIL} \
      --agree-tos \
      --no-eff-email \
      -d ${DOMAIN}
fi

if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    echo -e "${GREEN}✓ SSL sertifikası mevcut${NC}"

    # SSL config oluştur (sadece yoksa)
    if [ ! -f "docker/nginx/prod-ssl.conf" ]; then
        cat > docker/nginx/prod-ssl.conf << 'NGINX_SSL_EOF'
client_max_body_size 100M;
proxy_connect_timeout 600;
proxy_send_timeout 600;
proxy_read_timeout 600;
send_timeout 600;

server {
    listen 80;
    server_name DOMAIN_PLACEHOLDER;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name DOMAIN_PLACEHOLDER;
    root /var/www/html/public;

    ssl_certificate /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ^~ /storage/ {
        alias /var/www/html/storage/app/public/;
        try_files $uri =404;
        access_log off;
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
NGINX_SSL_EOF

        sed -i "s/DOMAIN_PLACEHOLDER/${DOMAIN}/g" docker/nginx/prod-ssl.conf
    fi

    echo -e "${GREEN}✓ SSL yapılandırması hazır${NC}"
else
    echo -e "${YELLOW}⚠ SSL alınamadı, HTTP ile devam ediliyor${NC}"
fi

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}   ✓ Kurulum Başarıyla Tamamlandı!      ${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "${BLUE}Erişim Bilgileri:${NC}"
if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    echo -e "URL: ${GREEN}https://${DOMAIN}${NC}"
else
    echo -e "URL: ${GREEN}http://${DOMAIN}${NC} veya ${GREEN}http://$(hostname -I | awk '{print $1}')${NC}"
fi
echo ""
echo -e "${BLUE}Veritabanı Bilgileri:${NC}"
echo -e "Host: ${GREEN}localhost${NC}"
echo -e "Port: ${GREEN}${DB_PORT}${NC}"
echo -e "Database: ${GREEN}${CLIENT_NAME}_db${NC}"
echo -e "Username: ${GREEN}${CLIENT_NAME}_user${NC}"
echo -e "Password: ${GREEN}${DB_PASSWORD}${NC}"
echo ""
echo -e "${YELLOW}APP_KEY: ${GREEN}${APP_KEY}${NC}"
echo ""
