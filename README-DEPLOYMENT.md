# Aytun Film AI - Kurulum Talimatları

## 📋 Değişen Dosyalar (GitHub'a Push Et)

Aşağıdaki dosyalar güncellendi ve GitHub'a push edilmeli:

1. **docker-compose.prod.yml** - Nginx'e storage volume eklendi
2. **deploy-ssl.sh** - Storage link ve izinler düzeltildi + --env-file eklendi

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
git clone https://github.com/KULLANICI_ADI/aytunfilmai.git aytunfilmai
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
- Domain adı: `example.com` (IP adresi DEĞIL!)
- SSL için email: `email@example.com`
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

### Görsellerin Çalıştığını Test Et
1. Admin panele gir: `https://example.com/admin/login`
2. Email: `admin@aytunfilmai.com`
3. Şifre: `admin123`
4. **Şifreyi hemen değiştir!**
5. Slider veya Scrolling Image yükle
6. Görselin sitede göründüğünü kontrol et

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

### Sorun: 500 Server Error
```bash
# Laravel loglarını kontrol et
docker compose -f docker-compose.prod.yml --env-file .env.production exec app tail -50 /var/www/html/storage/logs/laravel.log

# APP_KEY eksikse yeniden oluştur
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan key:generate --force
docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan config:clear
```

### Sorun: Container Başlamıyor
```bash
# Log'ları kontrol et
docker compose -f docker-compose.prod.yml --env-file .env.production logs

# Container'ları tamamen sil ve yeniden başlat
docker compose -f docker-compose.prod.yml --env-file .env.production down -v
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
```

---

## 📝 Admin Paneli Varsayılan Giriş

**İlk Kurulumda:**
- Email: `admin@aytunfilmai.com`
- Şifre: `admin123`

⚠️ **Güvenlik:** İlk girişten sonra mutlaka şifreyi değiştir!

---

## 🔐 Güvenlik Önerileri

1. **Admin şifresini hemen değiştir**
2. **MySQL portunu (3306) firewall'da kapat** (sadece localhost'tan erişim)
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
3. Storage izinleri doğru mu?
4. SSL sertifikası geçerli mi?

---

**Son Güncelleme:** 24 Ocak 2026
