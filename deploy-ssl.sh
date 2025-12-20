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
echo -e "${YELLOW}Şifreler otomatik oluşturuluyor...${NC}"

# Güvenli şifreler oluştur
DB_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
DB_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)

# .env.production dosyasını oluştur
echo -e "${BLUE}[1/10] .env dosyası oluşturuluyor...${NC}"
cat > .env.production << EOF
CLIENT_NAME=${CLIENT_NAME}
HTTP_PORT=80
HTTPS_PORT=443
DB_PORT=${DB_PORT}

APP_NAME="Aytun Film AI"
APP_ENV=production
APP_KEY=
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

# Docker container'ları başlat (önce HTTP ile)
echo -e "${BLUE}[2/10] Docker container'ları başlatılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ Docker container'ları başlatılamadı!${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Container'lar başlatıldı${NC}"

# Container'ların hazır olmasını bekle
echo -e "${BLUE}[3/10] Container'ların hazır olması bekleniyor...${NC}"
sleep 10

# APP_KEY oluştur
echo -e "${BLUE}[4/10] APP_KEY oluşturuluyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan key:generate --force

# Cache temizle
echo -e "${BLUE}[5/10] Cache temizleniyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan config:clear
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan cache:clear

# Migration çalıştır
echo -e "${BLUE}[6/10] Veritabanı migration'ları çalıştırılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan migrate --force

# Seeder çalıştır
echo -e "${BLUE}[7/10] Seeder'lar çalıştırılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan db:seed --force

# Storage link
echo -e "${BLUE}[8/10] Storage link oluşturuluyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan storage:link

# İzinleri düzelt
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app chmod -R 775 /var/www/html/storage

# Certbot kurulumu
echo -e "${BLUE}[9/10] Certbot kurulumu ve SSL sertifikası alınıyor...${NC}"
sudo apt update
sudo apt install certbot -y

# Certbot ile SSL al
sudo certbot certonly --webroot \
  --webroot-path=/var/www/${CLIENT_NAME}/public \
  --email ${SSL_EMAIL} \
  --agree-tos \
  --no-eff-email \
  -d ${DOMAIN}

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ SSL sertifikası alınamadı!${NC}"
    echo -e "${YELLOW}HTTP ile devam ediliyor...${NC}"
else
    echo -e "${GREEN}✓ SSL sertifikası alındı${NC}"

    # Nginx SSL konfigürasyonu oluştur
    echo -e "${BLUE}[10/10] SSL yapılandırması oluşturuluyor...${NC}"

    cat > docker/nginx/prod-ssl.conf << 'NGINX_SSL_EOF'
# Client body size (upload limiti)
client_max_body_size 100M;

# Timeout ayarları
proxy_connect_timeout 600;
proxy_send_timeout 600;
proxy_read_timeout 600;
send_timeout 600;

# HTTP to HTTPS redirect
server {
    listen 80;
    server_name DOMAIN_PLACEHOLDER;
    return 301 https://$server_name$request_uri;
}

# HTTPS server
server {
    listen 443 ssl http2;
    server_name DOMAIN_PLACEHOLDER;
    root /var/www/html/public;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/privkey.pem;

    # SSL Security
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Statik dosyalar için cache
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
NGINX_SSL_EOF

    # Domain placeholder'ı değiştir
    sed -i "s/DOMAIN_PLACEHOLDER/${DOMAIN}/g" docker/nginx/prod-ssl.conf

    # Docker compose'u SSL ile güncelle
    sed -i 's|./docker/nginx/prod.conf|./docker/nginx/prod-ssl.conf|g' docker-compose.prod.yml

    # SSL volume ekle
    sed -i '/volumes:/a\      - /etc/letsencrypt:/etc/letsencrypt:ro' docker-compose.prod.yml

    # Container'ları yeniden başlat
    docker-compose -f docker-compose.prod.yml --env-file .env.production down
    docker-compose -f docker-compose.prod.yml --env-file .env.production up -d

    echo -e "${GREEN}✓ SSL yapılandırması tamamlandı${NC}"
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
    echo -e "URL: ${GREEN}http://${DOMAIN}${NC}"
fi
echo ""
echo -e "${BLUE}Veritabanı Bilgileri:${NC}"
echo -e "Host: ${GREEN}localhost${NC}"
echo -e "Port: ${GREEN}${DB_PORT}${NC}"
echo -e "Database: ${GREEN}${CLIENT_NAME}_db${NC}"
echo -e "Username: ${GREEN}${CLIENT_NAME}_user${NC}"
echo -e "Password: ${GREEN}${DB_PASSWORD}${NC}"
echo ""
echo -e "${YELLOW}Not: Şifreler .env.production dosyasına kaydedildi${NC}"
echo ""
