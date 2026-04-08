#!/bin/bash
set -e

echo "🔄 Git pull..."
git pull origin main

echo "🐳 Docker yeniden başlatılıyor..."
docker compose -f docker-compose.prod.yml --env-file .env.docker down
docker compose -f docker-compose.prod.yml --env-file .env.docker up -d --build

echo "⏳ Container hazır olana kadar bekleniyor..."
sleep 10

echo "🗄️ Migration çalıştırılıyor..."
docker exec aytunfilmai_app php artisan migrate --force

echo "⚡ Optimize ediliyor..."
docker exec aytunfilmai_app php artisan optimize

echo "✅ Redeploy tamamlandı!"
