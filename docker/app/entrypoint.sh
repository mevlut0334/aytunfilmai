#!/bin/bash
set -e

# APP_KEY kontrol et ve oluştur
if grep -q "^APP_KEY=$" /var/www/html/.env; then
    echo "APP_KEY oluşturuluyor..."
    php artisan key:generate --force

    # Container içindeki .env'den APP_KEY'i al
    APP_KEY=$(grep ^APP_KEY= /var/www/html/.env | cut -d'=' -f2)

    # Host'taki .env.production dosyasına kaydet (sed yerine cp + mv)
    if [ -w /var/www/html/.env ]; then
        # Geçici dosyaya yazı
        grep -v "^APP_KEY=" /var/www/html/.env > /tmp/.env.tmp
        echo "APP_KEY=${APP_KEY}" >> /tmp/.env.tmp

        # Kopyala
        cp /tmp/.env.tmp /var/www/html/.env
        rm /tmp/.env.tmp

        echo "APP_KEY başarıyla kaydedildi: ${APP_KEY}"
    fi
fi

# php-fpm başlat
echo "PHP-FPM başlatılıyor..."
exec php-fpm
