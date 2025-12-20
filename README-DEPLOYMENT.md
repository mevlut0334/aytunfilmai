# Aytun Film AI - Docker VPS Deployment Rehberi

## Gereksinimler

VPS sunucuda:
- Ubuntu 20.04 veya üzeri
- En az 2GB RAM
- Docker ve Docker Compose
- Domain (SSL için)

## 1. Sunucuya Docker Kurulumu
```bash
# Docker kurulumu
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Docker Compose kurulumu
sudo apt-get update
sudo apt-get install docker-compose-plugin -y

# Docker'ı sudo olmadan kullanabilmek için
sudo usermod -aG docker $USER
newgrp docker

# Test
docker --version
docker compose version
```

## 2. Firewall Ayarları
```bash
# UFW kurulumu ve yapılandırması
sudo apt install ufw -y
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

## 3. Projeyi Sunucuya Yükleme

### Git ile (Önerilen)
```bash
# Proje klasörünü oluştur
sudo mkdir -p /var/www/aytunfilmai
sudo chown -R $USER:$USER /var/www/aytunfilmai

# Git clone
cd /var/www
git clone https://github.com/KULLANICI_ADI/aytunfilmai.git aytunfilmai
cd aytunfilmai
```

## 4. Deployment

### A) SSL OLMADAN (Sadece HTTP)
```bash
cd /var/www/aytunfilmai

# Script'e çalıştırma izni ver
chmod +x deploy.sh

# Deploy
./deploy.sh
```

Script sizden şunları soracak:
- Müşteri adı: aytunfilmai
- Domain/IP adresi: IP_ADRESI veya domain.com
- HTTP Port: 80
- MySQL Port: 3306

### B) SSL İLE (HTTPS)

**Önemli:** Domain DNS kayıtları sunucuya yönlendirilmiş olmalı!
```bash
cd /var/www/aytunfilmai

# Script'e çalıştırma izni ver
chmod +x deploy-ssl.sh

# Deploy
./deploy-ssl.sh
```

Script sizden şunları soracak:
- Müşteri adı: aytunfilmai
- Domain adı: domain.com
- SSL için email: email@example.com
- MySQL Port: 3306

## 5. Container'ları Yönetme
```bash
cd /var/www/aytunfilmai

# Container'ları görüntüle
docker compose -f docker-compose.prod.yml ps

# Log'ları görüntüle
docker compose -f docker-compose.prod.yml logs -f

# Container'ı yeniden başlat
docker compose -f docker-compose.prod.yml restart

# Container'ları durdur
docker compose -f docker-compose.prod.yml down

# Container'ları başlat
docker compose -f docker-compose.prod.yml up -d
```

## 6. Laravel Komutları
```bash
cd /var/www/aytunfilmai

# Migration çalıştır
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Seeder çalıştır
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# Cache temizle
docker compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker compose -f docker-compose.prod.yml exec app php artisan config:clear
docker compose -f docker-compose.prod.yml exec app php artisan route:clear
docker compose -f docker-compose.prod.yml exec app php artisan view:clear

# Optimize
docker compose -f docker-compose.prod.yml exec app php artisan optimize

# Storage izinlerini düzelt
docker compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html/storage
docker compose -f docker-compose.prod.yml exec app chmod -R 775 /var/www/html/storage
```

## 7. Veritabanı İşlemleri
```bash
# Veritabanı yedeği al
docker exec aytunfilmai_mysql mysqldump -u root -p'ROOT_PASSWORD' aytunfilmai_db > backup_$(date +%Y%m%d).sql

# Yedekten geri yükle
docker exec -i aytunfilmai_mysql mysql -u root -p'ROOT_PASSWORD' aytunfilmai_db < backup_20251215.sql

# MySQL'e bağlan
docker exec -it aytunfilmai_mysql mysql -u root -p
```

## 8. Proje Güncelleme (GitHub'dan)
```bash
cd /var/www/aytunfilmai

# Son değişiklikleri çek
git pull

# Container'ları yeniden build et
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d --build

# Migration çalıştır (gerekirse)
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Cache temizle ve optimize et
docker compose -f docker-compose.prod.yml exec app php artisan optimize
```

## 9. SSL Sertifikası Yenileme
```bash
# Manuel yenileme
sudo certbot renew

# Yenileme testi
sudo certbot renew --dry-run

# Container'ları yeniden başlat (sertifika yenilendikten sonra)
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml restart nginx
```

## 10. Sorun Giderme

### Container Çalışmıyor mu?
```bash
cd /var/www/aytunfilmai

# Container durumunu kontrol et
docker compose -f docker-compose.prod.yml ps

# Log'ları kontrol et
docker compose -f docker-compose.prod.yml logs app
docker compose -f docker-compose.prod.yml logs nginx
docker compose -f docker-compose.prod.yml logs mysql
```

### Site Açılmıyor mu?
```bash
# Nginx loglarını kontrol et
docker compose -f docker-compose.prod.yml logs nginx

# Laravel loglarını kontrol et
docker compose -f docker-compose.prod.yml exec app tail -f /var/www/html/storage/logs/laravel.log

# Firewall kontrol
sudo ufw status
```

### Veritabanı Bağlantı Hatası?
```bash
# MySQL container'ının çalıştığını kontrol et
docker compose -f docker-compose.prod.yml ps mysql

# MySQL'e bağlan ve test et
docker exec -it aytunfilmai_mysql mysql -u root -p
```

## 11. Sistem Kaynakları
```bash
# Container'ların kaynak kullanımını izle
docker stats

# Disk kullanımı
df -h

# RAM kullanımı
free -h

# CPU kullanımı
top
```

## 12. Güvenlik

### Fail2Ban Kurulumu (Brute Force koruması)
```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 13. TAM TEMİZLİK (Projeyi Tamamen Silmek)
```bash
cd /var/www/aytunfilmai

# Container'ları ve volume'leri sil
docker compose -f docker-compose.prod.yml down -v

# Proje klasörünü sil
cd ..
sudo rm -rf aytunfilmai

# Docker image'larını temizle (opsiyonel)
docker system prune -a
```

## Önemli Notlar

- `.env.production` dosyası hassas bilgiler içerir, GÜVENLİ TUTUN
- Düzenli yedek alın
- Log dosyalarını düzenli kontrol edin
- Güvenlik güncellemelerini takip edin
- SSL sertifikaları 90 günde bir yenilenir (otomatik)

## Deployment Scriptleri

- `deploy.sh` - HTTP deployment (SSL olmadan)
- `deploy-ssl.sh` - HTTPS deployment (SSL ile)

Her ikisi de otomatik kurulum yapar:
- Docker container'ları oluşturur
- Veritabanını kurar
- Migration ve seeder'ları çalıştırır
- SSL sertifikası alır (deploy-ssl.sh)
