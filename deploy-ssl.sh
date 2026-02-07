#!/bin/bash
echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

read -p "Domain (örn: example.com): " DOMAIN
read -p "SSL Email: " SSL_EMAIL

# -----------------------------
# Sunucu RAM ve Swap kontrolü
# -----------------------------
TOTAL_RAM=$(free -m | awk '/^Mem:/{print $2}')
echo "💾 Toplam RAM: ${TOTAL_RAM}MB"

if [ "$TOTAL_RAM" -lt 2000 ]; then
    if ! swapon --show | grep -q '/swapfile'; then
        echo "⚠ RAM düşük, 2GB swap oluşturuluyor..."
        fallocate -l 2G /swapfile
        chmod 600 /swapfile
        mkswap /swapfile
        swapon /swapfile
        echo '/swapfile none swap sw 0 0' | tee -a /etc/fstab
    else
        echo "✔ Swap zaten mevcut"
    fi
else
    echo "✔ Swap gerekli değil"
fi

# -----------------------------
# Repo işlemleri
# -----------------------------
PROJECT_DIR="/var/www/aytunfilmai"
if [ ! -d "$PROJECT_DIR" ]; then
    echo "📥 Repo clone..."
    git clone https://github.com/mevlut0334/aytunfilmai.git $PROJECT_DIR
else
    echo "📥 Repo update..."
    cd $PROJECT_DIR
    git fetch origin
    git reset --hard origin/main
fi

cd $PROJECT_DIR

# -----------------------------
# ENV ve APP_KEY
# -----------------------------
if [ ! -f .env.production ]; then
    cp .env.example .env.production
fi

php artisan key:generate --force --env=production

# -----------------------------
# Paket ve Nginx kurulumu
# -----------------------------
apt update -y && apt install -y nginx certbot python3-certbot-nginx curl git unzip

# -----------------------------
# Laravel storage izinleri
# -----------------------------
chown -R www-data:www-data $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage $PROJECT_DIR/bootstrap/cache

# -----------------------------
# Docker Compose prod başlatma
# -----------------------------
cd $PROJECT_DIR
docker compose -f docker-compose.prod.yml up -d --build

# -----------------------------
# Certbot SSL
# -----------------------------
certbot --nginx -d $DOMAIN --non-interactive --agree-tos --email $SSL_EMAIL

echo "===================================="
echo "✔ Deploy tamamlandı!"
echo "===================================="
