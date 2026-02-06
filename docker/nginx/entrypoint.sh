#!/bin/sh
set -e

echo "DOMAIN değişkeni ile nginx config oluşturuluyor..."
echo "DOMAIN: ${DOMAIN}"

# Template dosyasını envsubst ile işle
envsubst '${DOMAIN}' < /etc/nginx/conf.d/default-prod.conf.template > /etc/nginx/conf.d/default.conf

echo "Nginx config oluşturuldu:"
cat /etc/nginx/conf.d/default.conf | head -30

# Varsayılan nginx entrypoint devam etsin
exec /docker-entrypoint.sh "$@"
