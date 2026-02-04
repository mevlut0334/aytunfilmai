#!/bin/bash
set -e

# APP_KEY oluştur ve ortam değişkenine ata
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "APP_KEY oluşturuluyor..."
    php artisan key:generate --force

    # Oluşturulan APP_KEY'i al
    APP_KEY=$(grep ^APP_KEY= /var/www/html/.env | cut -d'=' -f2)
    export APP_KEY
    echo "APP_KEY set edildi: $APP_KEY"
fi

echo "PHP-FPM başlatılıyor..."
exec php-fpm
