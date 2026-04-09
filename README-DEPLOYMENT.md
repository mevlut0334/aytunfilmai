# Aytun Film AI - Kurulum Talimatları

## 📋 Son Güncellemeler (Şubat 2026)

- ✅ **Otomatik Kurulum** - Sadece 2 soru (Domain + Email)
- ✅ **Akıllı DB İsimlendirme** - Domain'den otomatik DB adı
- ✅ **RAM Kontrolü** - Düşük RAM'de otomatik swap oluşturma
- ✅ **Sistem Nginx Yönetimi** - Port çakışmasını önler
- ✅ **SSL Otomasyonu** - Certbot standalone + cron job
- ✅ **Bilgi Saklama** - Deployment bilgileri dosyaya kaydedilir
- ✅ **OPcache Temizleme** - Performance boost
- ✅ **PHP-FPM Optimization** - Worker processes (pm.max_children = 3)
- ✅ **Memory Optimization** - memory_limit = 256M
- ✅ **.env Persistence** - Volume mounting
- ✅ **Auto Restart** - `restart: unless-stopped`

---

## 🚀 Yeni Sunucuya Kurulum Adımları

### 1️⃣ Sunucuya SSH ile Bağlan
```bash
ssh ubuntu@SUNUCU_IP
```

### 2️⃣ Sistem Güncellemesi
```bash
sudo apt update && sudo apt upgrade -y
```

### 3️⃣ Docker Kurulumu
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo apt-get install docker-compose-plugin -y
sudo usermod -aG docker $USER
newgrp docker
```

### 4️⃣ Firewall Ayarları
```bash
sudo apt install ufw -y
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
# MySQL portunu AÇMA - Docker internal network kullanacak
sudo ufw --force enable
```

### 5️⃣ **Tek Komutla Otomatik Kurulum! 🎉**

**Önemli:** Domain DNS kayıtlarını önce sunucuya yönlendir!

```bash
curl -fsSL https://raw.githubusercontent.com/mevlut0334/aytunfilmai/main/deploy.sh | bash
```

veya
manuel kurulum daha stabil çalışır
```bash
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www

cd /var/www
git clone https://github.com/mevlut0334/aytunfilmai.git aytunfilmai
cd aytunfilmai
./deploy.sh
```

**Script Sadece 2 Soru Sorar:**
- **Domain adı:** `ornek.com`
- **SSL Email:** `admin@ornek.com`

Script otomatik olarak:
- ✅ Domain'den DB adı oluşturur (`ornek` -> `ornek_db`)
- ✅ Güvenli random şifreler üretir
- ✅ RAM kontrolü yapar, gerekirse swap ekler
- ✅ Sistem Nginx'i durdurur (port çakışması önlenir)
- ✅ SSL sertifikası alır (certbot standalone)
- ✅ Container'ları başlatır
- ✅ Migration + Seed çalıştırır
- ✅ Laravel'i optimize eder
- ✅ OPcache'i temizler
- ✅ SSL otomatik yenileme için cron job ekler
- ✅ Tüm bilgileri `~/deployment-info-ornek.com.txt` dosyasına kaydeder

---

## ✅ Kurulum Sonrası Kontroller

### 📊 Container'ları Kontrol Et
```bash
cd /var/www/aytunfilmai
docker ps
```

**Beklenen Çıktı:** 
```
aytunfilmai_nginx    Up
aytunfilmai_app      Up
aytunfilmai_mysql    Up (healthy)
```

### 📝 Deployment Bilgilerini Görüntüle
```bash
cat ~/deployment-info-ornek.com.txt
```

Bu dosyada:
- Database bilgileri (name, user, password, root password)
- APP_KEY
- Domain ve SSL bilgileri

### 📋 Log'ları İzle
```bash
docker logs aytunfilmai_app -f
docker logs aytunfilmai_nginx -f
docker logs aytunfilmai_mysql -f
```

### 🌐 Siteyi Test Et
1. Ana sayfa: `https://ornek.com`
2. Admin panel: `https://ornek.com/admin/login`
   - Email: `admin@aytunfilmai.com`
   - Şifre: `admin123`
   - **⚠️ Şifreyi hemen değiştir!**

---

## 🔧 Yararlı Yönetim Komutları

### Container Yönetimi

```bash
# Tüm container'ları göster
docker ps

# Container'ları durdur
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml down

# Container'ları başlat
docker compose -f docker-compose.prod.yml up -d

# Belirli bir container'ı restart et
docker restart aytunfilmai_nginx
docker restart aytunfilmai_app
docker restart aytunfilmai_mysql
```

### GitHub'dan Güncelleme

```bash
cd /var/www/aytunfilmai
git pull origin main
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d --build
docker exec aytunfilmai_app php artisan migrate --force
docker exec aytunfilmai_app php artisan config:clear
docker exec aytunfilmai_app php artisan optimize
```

### Laravel Komutları

```bash
# Cache temizle
docker exec aytunfilmai_app php artisan cache:clear
docker exec aytunfilmai_app php artisan config:clear
docker exec aytunfilmai_app php artisan view:clear
docker exec aytunfilmai_app php artisan event:clear

# Optimize et
docker exec aytunfilmai_app php artisan config:cache
docker exec aytunfilmai_app php artisan route:cache
docker exec aytunfilmai_app php artisan view:cache
docker exec aytunfilmai_app php artisan event:cache
docker exec aytunfilmai_app php artisan optimize

# Migration
docker exec aytunfilmai_app php artisan migrate --force

# Veritabanını sıfırla (⚠️ Tüm veriler silinir!)
docker exec aytunfilmai_app php artisan migrate:fresh --seed --force

# Storage link
docker exec aytunfilmai_app php artisan storage:link
```

### Veritabanı Yönetimi

```bash
# Veritabanı yedeği al
DB_NAME=$(grep "^DB Name:" ~/deployment-info-*.txt | awk '{print $3}')
DB_ROOT_PASS=$(grep "^DB Root Password:" ~/deployment-info-*.txt | awk '{print $4}')
docker exec aytunfilmai_mysql mysqldump -u root -p"${DB_ROOT_PASS}" ${DB_NAME} > backup_$(date +%Y%m%d_%H%M%S).sql

# Yedekten geri yükle
docker exec -i aytunfilmai_mysql mysql -u root -p"${DB_ROOT_PASS}" ${DB_NAME} < backup_20260207_120000.sql

# MySQL içine gir
docker exec -it aytunfilmai_mysql mysql -u root -p
```

### SSL Yönetimi

```bash
# SSL sertifikasını manuel yenile
sudo certbot renew

# SSL sertifikası durumunu kontrol et
sudo certbot certificates

# SSL yenileme cron job'ını kontrol et
crontab -l | grep certbot
```

---

## 🐛 Sorun Giderme

### ❌ Site Açılmıyor (500 Error veya Timeout)

```bash
# 1. Container'lar çalışıyor mu?
docker ps

# 2. Laravel log'larını kontrol et
docker exec aytunfilmai_app tail -100 /var/www/html/storage/logs/laravel.log

# 3. Nginx log'larını kontrol et
docker logs aytunfilmai_nginx --tail=50

# 4. .env dosyası var mı?
docker exec aytunfilmai_app cat /var/www/html/.env | head -5

# 5. Container'ları yeniden başlat
docker restart aytunfilmai_app aytunfilmai_nginx
```

### 🖼️ Görseller Yüklenmiyor

```bash
# Storage link'i kontrol et
docker exec aytunfilmai_app ls -la /var/www/html/public/storage

# Yoksa yeniden oluştur
docker exec aytunfilmai_app php artisan storage:link

# İzinleri düzelt
docker exec aytunfilmai_app chown -R www-data:www-data /var/www/html/storage
docker exec aytunfilmai_app chown -R www-data:www-data /var/www/html/public
docker exec aytunfilmai_app chmod -R 775 /var/www/html/storage
```

### 🔑 APP_KEY Hatası

```bash
# APP_KEY yeniden oluştur
docker exec aytunfilmai_app php artisan key:generate --force
docker exec aytunfilmai_app php artisan config:clear
docker restart aytunfilmai_app
```

### 🗄️ MySQL Bağlantı Hatası

```bash
# MySQL çalışıyor mu?
docker logs aytunfilmai_mysql --tail=30

# MySQL'i yeniden başlat
docker restart aytunfilmai_mysql

# 10 saniye bekle, sonra test et
sleep 10
docker exec aytunfilmai_app php artisan migrate:status
```

### 🐳 Container Başlamıyor

```bash
# Log'ları kontrol et
docker logs aytunfilmai_app
docker logs aytunfilmai_nginx
docker logs aytunfilmai_mysql

# Container'ları tamamen sil ve yeniden başlat
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml down -v
docker compose -f docker-compose.prod.yml up -d --build
```

### 🔒 SSL Hatası

```bash
# Certbot lock dosyalarını temizle
sudo pkill certbot
sudo rm -rf /tmp/certbot-*

# Sertifikayı manuel al
sudo certbot certonly --standalone --non-interactive --agree-tos --email admin@ornek.com -d ornek.com --http-01-port=80

# Nginx'i restart et
docker restart aytunfilmai_nginx
```

### 🔄 Sunucu Restart Sonrası

```bash
# Container'lar otomatik başladı mı kontrol et
docker ps

# Başlamadıysa manuel başlat
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml up -d

# Auto-restart policy kontrol et
docker inspect aytunfilmai_app | grep -A 3 RestartPolicy
```

### 💾 Düşük Disk Alanı

```bash
# Docker temizliği
docker system prune -a --volumes -f

# Eski image'ları sil
docker image prune -a -f

# Disk kullanımını kontrol et
df -h
docker system df
```

### 🧠 Yüksek RAM Kullanımı

```bash
# Container kaynak kullanımını göster
docker stats

# PHP-FPM worker sayısını azalt (zaten optimize edilmiş: 3)
# docker/php/php-fpm.conf dosyasında:
# pm.max_children = 3 (varsayılan)
# Gerekirse 2'ye düşürülebilir
```

---

## 🚨 Önemli Notlar

### 📂 Deployment Bilgileri Dosyası

Script çalıştıktan sonra:
```bash
~/deployment-info-ornek.com.txt
```

Bu dosyada **hassas bilgiler** var:
- ✅ Dosya izinleri: `600` (sadece siz okuyabilir)
- ⚠️ Bu dosyayı **GÜVENLİ** bir yere yedekleyin
- ⚠️ Dosyayı **asla** public repository'ye eklemeyin

### 🔐 .env Dosyası Güvenliği

- `.env` dosyası artık Docker volume olarak mount edilir
- Container restart edilse bile kaybolmaz ✅
- Sunucu restart edilse bile kalır ✅
- `/var/www/aytunfilmai/.env` konumundadır

### ⚡ PHP-FPM Optimizasyonları

Düşük RAM'li sunucular için optimize edilmiş:
- **pm.max_children = 3** (varsayılan)
- **memory_limit = 256M**
- **OPcache enabled** (PHP performance boost)

Eğer sunucunuzda 4GB+ RAM varsa:
```bash
# docker/php/php-fpm.conf dosyasını düzenle
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

# docker/php/php.ini dosyasını düzenle
memory_limit = 512M

# Container'ı rebuild et
docker compose -f docker-compose.prod.yml up -d --build
```

### 🔥 Firewall ve Güvenlik

**MySQL Portu:**
- Port `3306` firewall'da **AÇILMAMALI** ❌
- Docker internal network kullanır ✅
- Dışarıdan MySQL'e erişim gerekirse SSH tunnel kullanın

**SSH Tunnel Örneği:**
```bash
# Local makinenizden
ssh -L 3307:localhost:3306 ubuntu@SUNUCU_IP
# Artık localhost:3307'den MySQL'e erişebilirsiniz
```

### 🔄 Auto Restart Policy

Tüm container'lar `restart: unless-stopped` ile çalışır:
- ✅ Sunucu reboot edilirse container'lar otomatik başlar
- ✅ Container crash olursa otomatik restart olur
- ⚠️ `docker stop` ile durdurursanız, sunucu restart'ta başlamaz

### 📅 SSL Otomatik Yenileme

Script otomatik olarak cron job ekler:
```bash
# Her gece saat 03:00'te SSL yenileme kontrolü
0 3 * * * certbot renew --quiet --deploy-hook 'docker restart aytunfilmai_nginx'
```

Kontrol et:
```bash
crontab -l
```

---

## 📝 Admin Paneli Varsayılan Giriş

**İlk Kurulumda:**
- URL: `https://ornek.com/admin/login`
- Email: `admin@aytunfilmai.com`
- Şifre: `admin123`

⚠️ **Güvenlik:** İlk girişten sonra **MUTLAKA** şifreyi değiştir!

---

## 🔐 Güvenlik Checklist

Kurulumdan sonra yapılması gerekenler:

- [ ] Admin şifresini değiştir
- [ ] `~/deployment-info-*.txt` dosyasını güvenli yere yedekle
- [ ] SSH şifresi yerine SSH key kullan
- [ ] Firewall ayarlarını kontrol et (`sudo ufw status`)
- [ ] SSL sertifikası yenileme cron job'ını kontrol et (`crontab -l`)
- [ ] Düzenli veritabanı yedeği al (haftada 1-2 kez)
- [ ] `fail2ban` kur (brute force saldırılarına karşı)
- [ ] Log dosyalarını düzenli kontrol et

**Fail2ban Kurulumu (Opsiyonel):**
```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## 📞 Destek ve İletişim

Sorun yaşarsan kontrol et:

1. ✅ Container'lar çalışıyor mu? → `docker ps`
2. ✅ Log'larda hata var mı? → `docker logs aytunfilmai_app`
3. ✅ .env dosyası var mı? → `docker exec aytunfilmai_app cat /var/www/html/.env`
4. ✅ Storage izinleri doğru mu? → `docker exec aytunfilmai_app ls -la /var/www/html/storage`
5. ✅ SSL sertifikası geçerli mi? → `sudo certbot certificates`
6. ✅ Disk alanı yeterli mi? → `df -h`
7. ✅ RAM kullanımı normal mi? → `free -h`

**GitHub Issues:**
https://github.com/mevlut0334/aytunfilmai/issues

---

## 📚 Ek Kaynaklar

- [Laravel Resmi Dokümantasyonu](https://laravel.com/docs)
- [Docker Compose Dokümantasyonu](https://docs.docker.com/compose/)
- [Let's Encrypt Dokümantasyonu](https://letsencrypt.org/docs/)
- [Nginx Dokümantasyonu](https://nginx.org/en/docs/)

---

**Son Güncelleme:** 07 Şubat 2026
**Script Adı:** `deploy.sh` (v2.0)
**Kullanım:** Her sunucuya bir domain
**Minimum Sunucu Gereksinimleri:**
- RAM: 1GB (2GB+ önerilir)
- Disk: 10GB
- OS: Ubuntu 20.04 / 22.04 / 24.04
- Docker: 20.10+
- Docker Compose: 2.0+


güncelleme 

proje dizinindeyken    ./redeploy.sh  kodunu çalıştır

dizinde değilsen sunucuya bağlandıktan sonra   cd /var/www/aytunfilmai && ./redeploy.sh  kodunu çalıştır
