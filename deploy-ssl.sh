#!/bin/bash
echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

# DOMAIN ve EMAIL sor
read -p "Domain (örn: example.com): " DOMAIN
read -p "SSL Email: " SSL_EMAIL

# CLIENT_NAME ve DB bilgileri
read -p "Client Name (örn: aytunfilmai): " CLIENT_NAME
read -p "HTTP Port (örn: 80): " HTTP_PORT
read -p "DB Root Password: " DB_ROOT_PASSWORD
read -p "DB Name: " DB_DATABASE
read -p "DB User: " DB_USERNAME
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
if [ ! -d /var/www/$CLIENT_NAME ]; then
    echo "📥 Repo clone..."
    sudo git clone https://github.com/mevlut0334/aytunfilmai /var/www/$CLIENT_NAME
else
    echo "📥 Repo update..."
    cd /var/www/$CLIENT_NAME && sudo git fetch --all && sudo git reset --hard origin/main
fi

# ENV ve APP_KEY
cd /var/www/$CLIENT_NAME
if [ ! -f .env ]; then
    echo "⚙ ENV oluşturuluyor ve APP_KEY üretiliyor..."
    cp .env.example .env
    php artisan key:generate
else
    echo "✔ ENV dosyası zaten mevcut, APP_KEY korunuyor"
fi

# Laravel storage/cache izinleri
sudo chown -R www-data:www-data /var/www/$CLIENT_NAME
sudo chmod -R 775 /var/www/$CLIENT_NAME/storage /var/www/$CLIENT_NAME/bootstrap/cache

# Paketler
sudo apt update
sudo apt install -y nginx python3-certbot-nginx git curl unzip

# Certbot SSL kurulumu
sudo certbot --nginx -d $DOMAIN --non-interactive --agree-tos -m $SSL_EMAIL

# Docker Compose prod dosyasını oluştur
cat > /var/www/$CLIENT_NAME/docker-compose.prod.yml <<EOL
version: "3.9"

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile.prod
    container_name: ${CLIENT_NAME}_app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./storage:/var/www/html/storage
      - ./bootstrap/cache:/var/www/html/bootstrap/cache
      - ./.env:/var/www/html/.env
    environment:
      - APP_ENV=production
      - APP_KEY=$(php artisan key:generate --show)
    networks:
      - app_network
    depends_on:
      mysql:
        condition: service_healthy

  nginx:
    image: nginx:alpine
    container_name: ${CLIENT_NAME}_nginx
    restart: unless-stopped
    ports:
      - "${HTTP_PORT}:80"
      - "443:443"
    volumes:
      - ./public:/var/www/html/public:ro
      - ./storage:/var/www/html/storage:ro
      - ./docker/nginx/default-prod.conf.template:/etc/nginx/conf.d/default-prod.conf.template:ro
      - ./docker/nginx/entrypoint.sh:/docker-entrypoint.d/40-envsubst-domain.sh:ro
      - /etc/letsencrypt:/etc/letsencrypt:ro
    environment:
      - DOMAIN=${DOMAIN}
    networks:
      - app_network
    depends_on:
      - app

  mysql:
    image: mysql:8.0.35
    container_name: ${CLIENT_NAME}_mysql
    restart: unless-stopped
    ports:
      - "${DB_PORT}:3306"
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - app_network
    command: --default-authentication-plugin=mysql_native_password
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-p${DB_ROOT_PASSWORD}"]
      interval: 5s
      timeout: 5s
      retries: 20
      start_period: 30s

volumes:
  mysql_data:
    driver: local

networks:
  app_network:
    driver: bridge
EOL

# Docker Compose ile konteynerleri ayağa kaldır
cd /var/www/$CLIENT_NAME
docker compose -f docker-compose.prod.yml up -d --build

echo "✅ Deploy tamamlandı! Web siteniz hazır."
