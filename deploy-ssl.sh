#!/bin/bash
set -e

echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

########################################
# SABIT AYARLAR
########################################
REPO_URL="https://github.com/mevlut0334/aytunfilmai.git"
PROJECT_DIR="/var/www/aytunfilmai"
COMPOSE_FILE="docker-compose.prod.yml"
ENV_FILE=".env.production"

########################################
# INPUT
########################################
read -p "Domain (örn: example.com): " DOMAIN
read -p "SSL Email: " SSL_EMAIL

if [[ -z "$DOMAIN" || -z "$SSL_EMAIL" ]]; then
    echo "❌ Domain ve Email zorunlu"
    exit 1
fi

########################################
# SCRIPT & PROJE İZİNLERİ
########################################
# Scriptin kendisine çalıştırma izni ver
chmod +x "$0"

# Proje klasörünü user'a ayarla
sudo mkdir -p "$PROJECT_DIR"
sudo chown -R $USER:$USER "$PROJECT_DIR"
echo "✔ Script ve proje izinleri ayarlandı"

########################################
# RAM & SWAP KONTROL
########################################
TOTAL_RAM=$(grep MemTotal /proc/meminfo | awk '{print $2}')
TOTAL_RAM_MB=$((TOTAL_RAM / 1024))
echo "💾 Toplam RAM: ${TOTAL_RAM_MB}MB"

if [ "$TOTAL_RAM_MB" -lt 2048 ]; then
    if ! swapon --show | grep -q "/swapfile"; then
        echo "⚠ RAM düşük, 2GB swap oluşturuluyor..."
        sudo fallocate -l 2G /swapfile
        sudo chmod 600 /swapfile
        sudo mkswap /swapfile
        sudo swapon /swapfile
        echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
    else
        echo "✔ Swap zaten mevcut"
    fi
else
    echo "✔ RAM yeterli, swap eklemeye gerek yok"
fi

########################################
# DOCKER AUTO INSTALL
########################################
if ! command -v docker &> /dev/null; then
    echo "🐳 Docker kuruluyor..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo apt-get install docker-compose-plugin -y
    sudo usermod -aG docker $USER
    rm get-docker.sh
fi

if docker compose version &> /dev/null; then
    COMPOSE="docker compose"
else
    COMPOSE="docker-compose"
fi

########################################
# REPO CLONE / UPDATE
########################################
if [ ! -d "$PROJECT_DIR/.git" ]; then
    echo "📥 Repo clone..."
    git clone $REPO_URL "$PROJECT_DIR"
else
    echo "📥 Repo update..."
    cd "$PROJECT_DIR"
    git fetch origin main
    git reset --hard origin/main
fi

cd "$PROJECT_DIR"

########################################
# ENV & APP_KEY
########################################
if [ ! -f "$ENV_FILE" ]; then
    echo "⚙ ENV oluşturuluyor ve APP_KEY üretiliyor..."
    MYSQL_ROOT_PASS=$(openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 16)
    MYSQL_DB_PASS=$(openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 16)
    APP_KEY=$(openssl rand -base64 32)

    cat > "$ENV_FILE" <<EOL
APP_NAME=AytunFilmAI
APP_ENV=production
APP_KEY=base64:$APP_KEY
APP_DEBUG=false
APP_URL=https://$DOMAIN

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=aytunfilmai_db
DB_USERNAME=aytunfilmai_user
DB_PASSWORD=$MYSQL_DB_PASS

MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASS
MYSQL_DATABASE=aytunfilmai_db
MYSQL_USER=aytunfilmai_user
MYSQL_PASSWORD=$MYSQL_DB_PASS
EOL
else
    echo "✔ ENV dosyası zaten mevcut, APP_KEY korunuyor"
fi

########################################
# SSL INSTALL (opsiyonel)
########################################
sudo apt install certbot python3-certbot-nginx -y || true

########################################
# CONTAINERS START
########################################
echo "🐳 Docker containerlar başlatılıyor..."

# Docker memory limit (low RAM ise)
MEM_LIMIT="512m"
if [ "$TOTAL_RAM_MB" -ge 2048 ]; then
    MEM_LIMIT="1g"
fi

$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" down || true
$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d --build --memory "$MEM_LIMIT"

########################################
# WAIT DB
########################################
echo "⏳ MySQL hazır olana kadar bekleniyor..."
until $COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T mysql mysqladmin ping -h "mysql" --silent; do
    sleep 5
done
echo "✔ MySQL hazır"

########################################
# LARAVEL SETUP
########################################
echo "⚙ Laravel setup..."

$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan migrate --force
$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan db:seed --force
$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan config:cache
$COMPOSE -f "$COMPOSE_FILE" --env-file "$ENV_FILE" exec -T app php artisan route:cache

########################################
# SSL CERT
########################################
if [ ! -f "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" ]; then
    echo "🔒 SSL sertifikası kuruluyor..."
    sudo certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos -m "$SSL_EMAIL" || true
else
    echo "✔ SSL zaten kurulmuş"
fi

########################################
# DONE
########################################
echo ""
echo "===================================="
echo "🎉 DEPLOY TAMAMLANDI"
echo "===================================="
echo "🌐 Site: https://$DOMAIN"
echo "👤 Admin: admin@$DOMAIN"
echo "===================================="
