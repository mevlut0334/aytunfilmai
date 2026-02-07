#!/bin/bash
# ====================================
# 🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY
# ====================================

set -e

echo "===================================="
echo "🚀 SECURE & VPS-FRIENDLY AUTO DEPLOY"
echo "===================================="

# --- 1️⃣ Domain ve Client bilgisi ---
read -p "Domain (örn: example.com): " DOMAIN
read -p "Client Name (örn: aytunfilmai): " CLIENT_NAME
read -p "SSL Email: " SSL_EMAIL

# --- 2️⃣ Environment Değişkenleri ---
export CLIENT_NAME
export DOMAIN
export HTTP_PORT=80
export DB_PORT=3306
export DB_ROOT_PASSWORD="secret_root"  # Gerekirse prompt ile değiştir
export DB_DATABASE="${CLIENT_NAME}_db"
export DB_USERNAME="${CLIENT_NAME}"
export DB_PASSWORD="secret_db"         # Gerekirse prompt ile değiştir

# --- 3️⃣ RAM ve Swap Kontrol ---
TOTAL_RAM=$(free -m | awk '/^Mem:/{print $2}')
echo "💾 Toplam RAM: ${TOTAL_RAM}MB"

if [ "$TOTAL_RAM" -lt 2000 ]; then
    SWAP_FILE="/swapfile"
    if ! swapon --show | grep -q "$SWAP_FILE"; then
        echo "⚠ RAM düşük, 2GB swap oluşturuluyor..."
        sudo fallocate -l 2G $SWAP_FILE
        sudo chmod 600 $SWAP_FILE
        sudo mkswap $SWAP_FILE
        sudo swapon $SWAP_FILE
        echo "$SWAP_FILE none swap sw 0 0" | sudo tee -a /etc/fstab
    else
        echo "✔ Swap zaten mevcut"
    fi
fi

# --- 4️⃣ Proje Dizini ve İzinler ---
WEB_DIR="/var/www/${CLIENT_NAME}"
sudo mkdir -p "$WEB_DIR"
sudo chown -R $USER:$USER "$WEB_DIR"
sudo chmod -R 775 "$WEB_DIR"

# --- 5️⃣ Repo Clone / Update ---
if [ ! -d "$WEB_DIR/.git" ]; then
    echo "📥 Repo clone..."
    git clone https://github.com/mevlut0334/aytunfilmai "$WEB_DIR"
else
    echo "📥 Repo update..."
    cd "$WEB_DIR"
    git fetch --all
    git reset --hard origin/main
fi

# --- 6️⃣ ENV Dosyası ---
ENV_FILE="$WEB_DIR/.env.production"
if [ ! -f "$ENV_FILE" ]; then
    echo "⚙ ENV oluşturuluyor ve APP_KEY üretiliyor..."
    cp "$WEB_DIR/.env.example" "$ENV_FILE"
    APP_KEY=$(cd "$WEB_DIR" && php artisan key:generate --show)
    sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|" "$ENV_FILE"
fi

export APP_KEY=$(grep APP_KEY "$ENV_FILE" | cut -d '=' -f2)

# --- 7️⃣ Gerekli Paketler ---
sudo apt update
sudo apt install -y nginx python3-certbot-nginx git curl

# --- 8️⃣ Docker ve Container Başlatma ---
cd "$WEB_DIR"

# DEV veya PROD docker-compose dosyasına göre seç
COMPOSE_FILE="docker-compose.prod.yml"
if [ ! -f "$COMPOSE_FILE" ]; then
    echo "❌ $COMPOSE_FILE bulunamadı, script sonlandırılıyor"
    exit 1
fi

echo "🐳 Docker containerlar başlatılıyor..."
docker-compose -f "$COMPOSE_FILE" up -d --build

# --- 9️⃣ Dosya izinleri ve storage ayarları ---
sudo chown -R www-data:www-data "$WEB_DIR"
sudo chmod -R 775 "$WEB_DIR/storage" "$WEB_DIR/bootstrap/cache"

# --- 10️⃣ SSL (Let's Encrypt) ---
echo "🔒 SSL kurulumu..."
sudo certbot --nginx -d "$DOMAIN" --email "$SSL_EMAIL" --agree-tos --non-interactive

echo "===================================="
echo "✅ Deploy tamamlandı!"
echo "===================================="
