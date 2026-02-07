# 🎬 Aytun Film AI

Laravel tabanlı yapay zeka destekli film oluşturma platformu. Tek komutla otomatik deployment, SSL desteği ve Docker container yönetimi.

[![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)
[![License](https://img.shields.io/badge/License-Private-yellow.svg)](LICENSE)

---

## 🚀 Özellikler

### 🎥 AI Film Oluşturma
- Yapay zeka destekli video üretimi
- Otomatik senaryo analizi
- Dinamik içerik yönetimi

### 💳 Ödeme Sistemi
- Havale ile güvenli ödeme
- Otomatik ödeme onayı
- Detaylı sipariş takibi

### 🎨 Modern Arayüz
- Responsive tasarım (mobil uyumlu)
- Kullanıcı dostu panel
- Gerçek zamanlı bildrimler

### 🔐 Güvenlik
- SSL/HTTPS desteği
- Güvenli admin paneli
- Rol tabanlı yetkilendirme

### 📊 Yönetim
- Kapsamlı admin paneli
- Kullanıcı yönetimi
- İstatistik ve raporlama

---

## 🛠️ Teknoloji Stack

### Backend
- **Framework:** Laravel 11
- **PHP:** 8.3-FPM (OPcache enabled)
- **Database:** MySQL 8.0
- **Cache:** Database-based caching

### DevOps
- **Containerization:** Docker + Docker Compose
- **Web Server:** Nginx 1.25
- **SSL:** Let's Encrypt (Certbot)
- **Auto Deploy:** Bash script (`deploy.sh`)

### Optimizasyonlar
- ✅ PHP-FPM (pm.max_children = 3)
- ✅ OPcache aktif
- ✅ Memory limit: 256M
- ✅ Auto restart policy
- ✅ .env persistence

---

## ⚡ Hızlı Kurulum

### Ön Gereksinimler

- **Sunucu:** Ubuntu 20.04 / 22.04 / 24.04
- **RAM:** Minimum 1GB (2GB+ önerilir)
- **Disk:** Minimum 10GB
- **Docker:** 20.10+ (kurulum adımlarında anlatılıyor)
- **Domain:** SSL için gerekli (DNS A kaydı yapılmış)

### 1️⃣ Tek Komutla Kurulum

```bash
curl -fsSL https://raw.githubusercontent.com/mevlut0334/aytunfilmai/main/deploy.sh | bash
```

**veya**

```bash
# Repository'yi klonla
git clone https://github.com/mevlut0334/aytunfilmai.git
cd aytunfilmai

# Deploy script'ini çalıştır
./deploy.sh
```

### 2️⃣ Script Soruları

Script **sadece 2 soru** sorar:

1. **Domain:** `ornek.com`
2. **SSL Email:** `admin@ornek.com`

Geri kalanı otomatik:
- ✅ Domain'den DB adı oluşturur
- ✅ Güvenli şifreler üretir
- ✅ RAM kontrolü + swap
- ✅ SSL sertifikası alır
- ✅ Container'ları başlatır
- ✅ Veritabanını kurar
- ✅ Laravel'i optimize eder

### 3️⃣ Kurulum Tamamlandı! 🎉

Site hazır: `https://ornek.com`

**Deployment bilgileri:**
```bash
cat ~/deployment-info-ornek.com.txt
```

---

## 📋 Detaylı Kurulum

Adım adım kurulum, sorun giderme ve gelişmiş ayarlar için:

**→ [KURULUM.md](KURULUM.md)**

İçindekiler:
- Docker kurulumu
- Firewall ayarları
- SSL yapılandırması
- Sorun giderme
- Güvenlik tavsiyeleri

---

## 🔐 Varsayılan Giriş Bilgileri

### Admin Panel

**URL:** `https://ornek.com/admin/login`

- **Email:** `admin@aytunfilmai.com`
- **Şifre:** `admin123`

⚠️ **UYARI:** İlk girişten sonra **MUTLAKA** şifreyi değiştirin!

---

## 🎯 Kullanım

### Container Yönetimi

```bash
# Container durumunu kontrol et
docker ps

# Container'ları durdur
cd /var/www/aytunfilmai
docker compose -f docker-compose.prod.yml down

# Container'ları başlat
docker compose -f docker-compose.prod.yml up -d

# Log'ları izle
docker logs aytunfilmai_app -f
```

### Laravel Komutları

```bash
# Cache temizle
docker exec aytunfilmai_app php artisan cache:clear
docker exec aytunfilmai_app php artisan config:clear
docker exec aytunfilmai_app php artisan view:clear

# Optimize et
docker exec aytunfilmai_app php artisan optimize

# Migration
docker exec aytunfilmai_app php artisan migrate --force
```

### Veritabanı Yedeği

```bash
# Yedek al
docker exec aytunfilmai_mysql mysqldump -u root -p'ROOT_PASSWORD' DB_NAME > backup.sql

# Geri yükle
docker exec -i aytunfilmai_mysql mysql -u root -p'ROOT_PASSWORD' DB_NAME < backup.sql
```

> **Not:** ROOT_PASSWORD ve DB_NAME bilgileri `~/deployment-info-*.txt` dosyasında.

---

## 🔄 Güncelleme

GitHub'dan en son değişiklikleri çek:

```bash
cd /var/www/aytunfilmai
git pull origin main
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d --build
docker exec aytunfilmai_app php artisan migrate --force
docker exec aytunfilmai_app php artisan optimize
```

---

## 🐛 Sorun Giderme

### Site açılmıyor?

```bash
# Container'ları kontrol et
docker ps

# Log'ları incele
docker logs aytunfilmai_app
docker logs aytunfilmai_nginx
```

### Veritabanı hatası?

```bash
# MySQL'i restart et
docker restart aytunfilmai_mysql

# Connection test
docker exec aytunfilmai_app php artisan migrate:status
```

### SSL hatası?

```bash
# Certbot lock temizle
sudo pkill certbot
sudo rm -rf /tmp/certbot-*

# Nginx restart
docker restart aytunfilmai_nginx
```

**Daha fazla sorun giderme:** [KURULUM.md → Sorun Giderme](KURULUM.md#-sorun-giderme)

---

## 📁 Proje Yapısı

```
aytunfilmai/
├── app/                    # Laravel uygulama kodu
├── database/               # Migration ve seeder'lar
├── docker/                 # Docker yapılandırmaları
│   ├── nginx/             # Nginx config + entrypoint
│   ├── php/               # PHP-FPM config
│   └── mysql/             # MySQL init scripts
├── public/                # Public dosyalar
├── resources/             # Views, CSS, JS
├── routes/                # Route tanımlamaları
├── storage/               # Loglar, cache, uploads
├── docker-compose.prod.yml # Production Docker Compose
├── Dockerfile.prod        # Production Dockerfile
├── deploy.sh              # Otomatik deployment script ⭐
├── .env.example           # Örnek environment dosyası
├── KURULUM.md             # Detaylı kurulum kılavuzu
└── README.md              # Bu dosya
```

---

## 🔐 Güvenlik

### Kurulumdan Sonra Yapılması Gerekenler

- [x] Admin şifresini değiştir
- [x] `~/deployment-info-*.txt` dosyasını güvenli yere yedekle
- [x] SSH key authentication kullan
- [x] Firewall ayarlarını kontrol et
- [x] Düzenli veritabanı yedeği al
- [ ] Fail2ban kur (brute force koruması)
- [ ] Two-factor authentication ekle (opsiyonel)

### Güvenlik Özellikleri

- ✅ SSL/HTTPS (Let's Encrypt)
- ✅ MySQL sadece internal network'te
- ✅ Güvenli .env yönetimi
- ✅ CSRF koruması
- ✅ XSS koruması
- ✅ SQL injection koruması
- ✅ Password hashing (bcrypt)

---

## 🚀 Performans

### Optimizasyonlar

Script otomatik olarak:
- ✅ OPcache'i aktif eder
- ✅ Laravel config/route/view cache'i oluşturur
- ✅ PHP-FPM worker'ları optimize eder
- ✅ Nginx gzip compression aktif

### Benchmark (Örnek)

| Metrik | Değer |
|--------|-------|
| Ortalama yanıt süresi | ~50ms |
| Concurrent users | 100+ |
| Memory usage | ~180MB |
| CPU usage (idle) | ~5% |

> Test ortamı: 2GB RAM, 2 vCPU

---

## 📊 Sistem Gereksinimleri

### Minimum

- **CPU:** 1 vCPU
- **RAM:** 1GB (swap ile)
- **Disk:** 10GB
- **OS:** Ubuntu 20.04+

### Önerilen

- **CPU:** 2+ vCPU
- **RAM:** 2GB+
- **Disk:** 20GB+ SSD
- **OS:** Ubuntu 22.04 / 24.04

### Network

- Port 80 (HTTP)
- Port 443 (HTTPS)
- Port 22 (SSH)

---

## 🤝 Katkıda Bulunma

Bu proje şu an **private** durumdadır. Katkıda bulunmak için lütfen repository sahibi ile iletişime geçin.

---

## 📄 Lisans

Bu proje özel lisans altındadır. Tüm hakları saklıdır.

---

## 📞 İletişim ve Destek

### Sorun Bildirimi

GitHub Issues: [github.com/mevlut0334/aytunfilmai/issues](https://github.com/mevlut0334/aytunfilmai/issues)

### Kontrol Listesi

Sorun bildirmeden önce kontrol et:

- [ ] Container'lar çalışıyor mu? (`docker ps`)
- [ ] Log'larda hata var mı? (`docker logs aytunfilmai_app`)
- [ ] .env dosyası mevcut mu?
- [ ] Disk alanı yeterli mi? (`df -h`)
- [ ] SSL sertifikası geçerli mi? (`sudo certbot certificates`)

---

## 🎓 Kaynaklar

### Dokümantasyon

- [Laravel Resmi Dokümantasyonu](https://laravel.com/docs)
- [Docker Compose Dokümantasyonu](https://docs.docker.com/compose/)
- [Nginx Dokümantasyonu](https://nginx.org/en/docs/)

### Faydalı Komutlar

```bash
# Sistem bilgisi
docker stats
free -h
df -h
docker system df

# Container shell
docker exec -it aytunfilmai_app bash
docker exec -it aytunfilmai_mysql mysql -u root -p

# Deployment bilgileri
cat ~/deployment-info-*.txt

# SSL durum
sudo certbot certificates
```

---

## 📈 Changelog

### v2.0 (Şubat 2026)
- ✨ Otomatik deployment script (`deploy.sh`)
- ✨ Sadece 2 soru (Domain + Email)
- ✨ RAM kontrolü + otomatik swap
- ✨ SSL otomatik yenileme cron job
- ✨ Deployment bilgileri dosyaya kaydetme
- ⚡ OPcache temizleme
- ⚡ PHP-FPM optimizasyonu
- 🐛 .env persistence sorunu çözüldü

### v1.0 (Ocak 2026)
- 🎉 İlk release
- ✅ Docker containerization
- ✅ SSL desteği
- ✅ Admin paneli

---

## ⭐ Özellikler Roadmap

### Planlanan
- [ ] Redis cache desteği
- [ ] Elasticsearch entegrasyonu
- [ ] CDN desteği
- [ ] Backup otomasyonu
- [ ] Monitoring (Prometheus + Grafana)
- [ ] CI/CD pipeline (GitHub Actions)

### Geliştirilmekte
- [ ] API documentation (Swagger)
- [ ] Unit & Feature tests
- [ ] Multi-language support

---

## 💡 SSS (Sık Sorulan Sorular)

### Her sunucuya farklı domain kurabilir miyim?

**Evet!** Bu script tam olarak bunun için tasarlanmıştır. Her sunucuda ayrı bir domain çalıştırabilirsiniz:

```bash
# Sunucu 1 (89.252.153.163)
./deploy.sh
Domain: site1.com
✅ Çalışır

# Sunucu 2 (farklı IP)
./deploy.sh
Domain: site2.com
✅ Çalışır

# Sunucu 3 (farklı IP)
./deploy.sh
Domain: site3.com
✅ Çalışır
```

### Deployment bilgilerimi kaybettim, nasıl bulabilirim?

```bash
cat ~/deployment-info-*.txt
```

veya

```bash
# .env dosyasından
docker exec aytunfilmai_app cat /var/www/html/.env | grep DB_
docker exec aytunfilmai_app cat /var/www/html/.env | grep APP_KEY
```

### SSL sertifikası otomatik yenileniyor mu?

Evet, `deploy.sh` scripti otomatik cron job ekler:
```bash
crontab -l | grep certbot
```

### Container'ları sunucu restart'ında otomatik başlatabilir miyim?

Evet, zaten aktif! Tüm container'larda `restart: unless-stopped` policy var.

### Veritabanı yedeği nasıl alırım?

```bash
docker exec aytunfilmai_mysql mysqldump -u root -p'PASSWORD' DB_NAME > backup.sql
```

Password ve DB_NAME: `~/deployment-info-*.txt` dosyasında

---

**🎬 Aytun Film AI - AI Powered Video Creation Platform**

**Son Güncelleme:** 07 Şubat 2026  
**Script Adı:** `deploy.sh` (v2.0)  
**Kullanım:** Her sunucuya bir domain  
**Geliştirici:** mevluttuncer0334@gmail.com

---

<div align="center">

Made with ❤️ using Laravel + Docker

[🌐 Demo](https://aytunfilmai.com) • [📖 Dokümantasyon](KURULUM.md) • [🐛 Issues](https://github.com/mevlut0334/aytunfilmai/issues)

</div>
