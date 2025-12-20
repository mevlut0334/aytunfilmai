#!/bin/bash

# Renkler
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}=========================================${NC}"
echo -e "${BLUE}  Aytun Film AI Docker Deployment       ${NC}"
echo -e "${BLUE}=========================================${NC}"
echo ""

# Müşteri bilgilerini al
read -p "Müşteri adı (örn: aytunfilmai): " CLIENT_NAME
read -p "Domain veya IP adresi (örn: example.com veya 89.252.153.179): " DOMAIN
read -p "HTTP Port (varsayılan 80): " HTTP_PORT
HTTP_PORT=${HTTP_PORT:-80}
read -p "MySQL Port (varsayılan 3306): " DB_PORT
DB_PORT=${DB_PORT:-3306}

echo ""
echo -e "${YELLOW}Şifreler otomatik oluşturuluyor...${NC}"

# Güvenli şifreler oluştur
DB_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
DB_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)

# .env.production dosyasını oluştur
echo -e "${BLUE}[1/8] .env dosyası oluşturuluyor...${NC}"
cat > .env.production << EOF
CLIENT_NAME=${CLIENT_NAME}
HTTP_PORT=${HTTP_PORT}
DB_PORT=${DB_PORT}

APP_NAME="Aytun Film AI"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://${DOMAIN}

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
echo -e "${BLUE}[2/8] Docker container'ları başlatılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ Docker container'ları başlatılamadı!${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Container'lar başlatıldı${NC}"

# Container'ların hazır olmasını bekle
echo -e "${BLUE}[3/8] Container'ların hazır olması bekleniyor...${NC}"
sleep 10

# APP_KEY oluştur
echo -e "${BLUE}[4/8] APP_KEY oluşturuluyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan key:generate --force

# Cache temizle
echo -e "${BLUE}[5/8] Cache temizleniyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan config:clear
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan cache:clear

# Migration çalıştır
echo -e "${BLUE}[6/8] Veritabanı migration'ları çalıştırılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan migrate --force

# Seeder çalıştır
echo -e "${BLUE}[7/8] Seeder'lar çalıştırılıyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan db:seed --force

# Storage link
echo -e "${BLUE}[8/8] Storage link oluşturuluyor...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app php artisan storage:link

# İzinleri düzelt
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose -f docker-compose.prod.yml --env-file .env.production exec -T app chmod -R 775 /var/www/html/storage

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}   ✓ Kurulum Başarıyla Tamamlandı!      ${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "${BLUE}Erişim Bilgileri:${NC}"
echo -e "URL: ${GREEN}http://${DOMAIN}:${HTTP_PORT}${NC}"
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
