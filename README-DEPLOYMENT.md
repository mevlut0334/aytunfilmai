# Aytun Film AI - Kurulum Talimatları

## 📋 Son Güncellemeler

- ✅ **PHP-FPM Optimization** - Worker processes (pm.max_children = 3)
- ✅ **OPcache** - Performance improvement
- ✅ **Memory Optimization** - memory_limit = 256M (low-resource servers için)
- ✅ **.env Persistence** - Volume mounting (container restart'ta kaybolmaz)
- ✅ **Auto Restart** - `restart: unless-stopped` (sunucu restart'ta otomatik başlar)

---

## 🚀 Yeni Sunucuya Kurulum Adımları

### 1. Sunucuya SSH ile Bağlan
```bash
ssh ubuntu@SUNUCU_IP
```

### 2. Sistem Güncellemesi
```bash
sudo apt update && sudo apt upgrade -y
```

### 3. Docker Kurulumu
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo apt-get install docker-compose-plugin -y
sudo usermod -aG docker $USER
newgrp docker
```

### 4. Firewall Ayarları
```bash
sudo apt install ufw -y
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
# MySQL portunu AÇMA - Docker internal network kullanacak
sudo ufw --force enable
```

### 5. Proje Klasörü Oluştur
```bash
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www
```

### 6. GitHub'dan Projeyi Çek
```bash
cd /var/www
git clone https://github.com/mevlut0334/aytunfilmai.git aytunfilmai
cd aytunfilmai
```

### 7. SSL ile Kurulum (Domain Gerekli)

**Önemli:** Domain DNS kayıtlarını önce sunucuya yönlendir!
```bash
chmod +x deploy-ssl.sh
./deploy-ssl.sh
```

**Script Soruları:**
- Müşteri adı: `aytunfilmai`
- Domain adı: `aytunfilmai.com`
- SSL için email: `admin@aytunfilmai.com`
- MySQL Port: `3306`

---

## ✅ Kurulum Sonrası Kontroller

### Container'ları Kontrol Et
```bash
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml --env-file .env.production ps
```

**Beklenen Çıktı:** Tüm container'lar "Up" durumunda

### Log'ları İzle
```bash
docker compose -f docker-compose.prod.yml --env-file .env.production logs -f
```

### Siteyi Test Et
1. Ana sayfa: `https://aytunfilmai.com`
2. Admin panel: `https://aytunfilmai.com/admin/login`
   - Email: `admin@aytunfilmai.com`
   - Şifre: `admin123`
   - **Şifreyi hemen değiştir!**

---

## 🔧 Yararlı Yönetim Komutları

**ÖNEMLİ:** Her komutta `--env-file .env.production` kullan!

### Container'ları Durdur
```bash
docker compose -f docker-compose.prod.yml --env-file .env.production down
```

### Container'ları Başlat
```bash
docker compose -f docker-compose.prod.yml --env-file .env.production up -d
```

### Güncellemeleri GitHub'dan Çek
```bash
cd /var/www/aytunfilmai
git pull origin main
docker compose -f docker-compose.prod.yml --env-file .env.production down
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan config:clear
```

### Veritabanını Sıfırla (Tüm Veriler Silinir!)
```bash
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan migrate:fresh --seed --force
```

### Cache Temizle
```bash
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan cache:clear
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan config:clear
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan view:clear
```

### Veritabanı Yedeği Al
```bash
docker exec aytunfilmai_mysql mysqldump -u root -p'ROOT_PASSWORD' aytunfilmai_db > backup_$(date +%Y%m%d).sql
```

**Not:** `ROOT_PASSWORD` yerine `.env.production` dosyasındaki `DB_ROOT_PASSWORD` değerini kullan.

---

## 🐛 Sorun Giderme

### Sorun: Site Açılmıyor (500 Error veya Timeout)
```bash
# 1. Container'lar çalışıyor mu?
docker compose -f docker-compose.prod.yml --env-file .env.production ps

# 2. Laravel log'larını kontrol et
docker compose -f docker-compose.prod.yml --env-file .env.production exec app tail -100 /var/www/html/storage/logs/laravel.log

# 3. .env dosyası var mı?
docker compose -f docker-compose.prod.yml --env-file .env.production exec app cat /var/www/html/.env | head -5

# 4. .env yoksa (Bu durumda container'ları yeniden başlat)
docker compose -f docker-compose.prod.yml --env-file .env.production down
docker compose -f docker-compose.prod.yml --env-file .env.production up -d
```

### Sorun: Görseller Yüklenmiyor
```bash
# Storage link'i kontrol et
docker compose -f docker-compose.prod.yml --env-file .env.production exec app ls -la /var/www/html/public/storage

# Yoksa yeniden oluştur
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan storage:link

# İzinleri düzelt
docker compose -f docker-compose.prod.yml --env-file .env.production exec app chown -R www-data:www-data /var/www/html/storage
docker compose -f docker-compose.prod.yml --env-file .env.production exec app chown -R www-data:www-data /var/www/html/public
docker compose -f docker-compose.prod.yml --env-file .env.production exec app chmod -R 775 /var/www/html/storage
```

### Sorun: APP_KEY Hatası
```bash
# APP_KEY yeniden oluştur
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan key:generate --force
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan config:clear
docker compose -f docker-compose.prod.yml --env-file .env.production restart
```

### Sorun: MySQL Bağlantı Hatası
```bash
# MySQL çalışıyor mu?
docker compose -f docker-compose.prod.yml --env-file .env.production logs mysql --tail=30

# MySQL'i yeniden başlat
docker compose -f docker-compose.prod.yml --env-file .env.production restart mysql

# 10 saniye bekle, sonra test et
sleep 10
curl -I https://aytunfilmai.com
```

### Sorun: Container Başlamıyor
```bash
# Log'ları kontrol et
docker compose -f docker-compose.prod.yml --env-file .env.production logs

# Container'ları tamamen sil ve yeniden başlat
docker compose -f docker-compose.prod.yml --env-file .env.production down -v
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
```

### Sorun: Bağlantı Kopması / Sunucu Restart'ı
```bash
# Container'lar otomatik başlasın mı kontrol et
docker inspect aytunfilmai_app | grep RestartPolicy

# Eğer otomatik başlamıyorsa, manuel başlat
docker compose -f docker-compose.prod.yml --env-file .env.production up -d
```

---

## 🚨 Önemli Notlar

### .env Dosyası (Yeni!)
- `.env.production` dosyası artık `docker-compose.prod.yml` içinde **volume** olarak bağlanmış
- Container restart edilse bile `.env` kaybolmaz ✅
- Sunucu restart edilse bile `.env` kalır ✅
- **Bu sorunun kalıcı çözümü sağlanmıştır!**

### PHP-FPM Optimizasyonları (Yeni!)
- **pm.max_children = 3** (low-resource servers için optimize)
- **memory_limit = 256M** (önceki 512M'den azaltıldı)
- **OPcache enabled** (performance boost)
- **Dockerfile.prod'da php-fpm.conf mount edilir**

### Firewall ve MySQL
- MySQL portu (3306) firewall'da **AÇILMAMALI**
- Docker container'ları internal network üzerinden iletişim kurar
- Eğer dışarıdan MySQL'e erişim gerekirse SSH tunnel kullanın

### Auto Restart
- Tüm container'larda `restart: unless-stopped` aktif
- Sunucu restart edilirse container'lar otomatik başlar

---

## 📝 Admin Paneli Varsayılan Giriş

**İlk Kurulumda:**
- Email: `admin@aytunfilmai.com`
- Şifre: `admin123`

⚠️ **Güvenlik:** İlk girişten sonra mutlaka şifreyi değiştir!

---

## 🔐 Güvenlik Önerileri

1. **Admin şifresini hemen değiştir**
2. **MySQL portunu firewall'da açma** (Docker internal network kullan)
3. **SSH şifresi yerine SSH key kullan**
4. **Düzenli veritabanı yedeği al**
5. **SSL sertifikasını otomatik yenile** (Let's Encrypt 90 günde bir)
```bash
# SSL otomatik yenileme için cron job ekle
sudo crontab -e

# Şu satırı ekle:
0 3 * * * certbot renew --quiet && docker compose -f /var/www/aytunfilmai/docker-compose.prod.yml --env-file /var/www/aytunfilmai/.env.production restart nginx
```

---

## 📞 Destek

Sorun yaşarsan kontrol et:
1. Container'lar çalışıyor mu? (`docker compose ps`)
2. Log'larda hata var mı? (`docker compose logs`)
3. .env dosyası var mı? (`docker compose exec app cat /var/www/html/.env`)
4. Storage izinleri doğru mu?
5. SSL sertifikası geçerli mi?

---

**Son Güncelleme:** 03 Şubat 2026 - PHP-FPM, OPcache ve .env Persistence optimizasyonları eklendi
