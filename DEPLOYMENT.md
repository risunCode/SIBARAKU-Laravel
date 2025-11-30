# 🚀 SIBARAKU - Panduan Deployment

**Versi: v1.1.2-public-hotfix**

Dokumen ini berisi instruksi terperinci tentang cara men-deploy SIBARAKU di berbagai lingkungan.

---

## 📋 Daftar Isi

- [Mode Environment](#-mode-environment)
- [Persiapan Awal](#persiapan-awal)
- [Deployment Lokal](#deployment-lokal)
- [Deployment dengan ngrok](#deployment-dengan-ngrok)
- [Deployment dengan Cloudflare Tunnel](#deployment-dengan-cloudflare-tunnel)
- [Shared Hosting](#shared-hosting)
- [VPS/Dedicated Server](#vpsdedicated-server)
- [Docker](#docker)
- [Konfigurasi Tambahan](#konfigurasi-tambahan)
- [Checklist Deployment](#checklist-deployment)
- [Pemecahan Masalah](#pemecahan-masalah)

---

## 🎯 Mode Environment

SIBARAKU mendukung 4 mode environment. Lihat `.env.example` untuk konfigurasi lengkap.

```
┌─────────────────┬────────────────────────────────────────────────────────┐
│ MODE            │ DESKRIPSI                                              │
├─────────────────┼────────────────────────────────────────────────────────┤
│ [LOCAL]         │ Development di localhost:8000                          │
│ [NGROK]         │ Testing via ngrok tunnel (URL publik sementara)        │
│ [CLOUDFLARE]    │ Testing via Cloudflare Tunnel (gratis & stabil)        │
│ [PRODUCTION]    │ Deployment produksi (server asli)                      │
└─────────────────┴────────────────────────────────────────────────────────┘
```

### Perbedaan Konfigurasi Utama

| Setting | [LOCAL] | [NGROK] | [CLOUDFLARE] | [PRODUCTION] |
|---------|---------|---------|--------------|--------------|
| `APP_ENV` | local | local | local | production |
| `APP_DEBUG` | true | false | false | **false** |
| `APP_URL` | http://localhost:8000 | https://xxx.ngrok-free.dev | https://xxx.trycloudflare.com | https://domain.com |
| `LOG_LEVEL` | debug | info | info | warning |

---

## Persiapan Awal

### Prasyarat
- PHP 8.2+ dengan ekstensi berikut:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer 2.x
- Node.js 18+ dan NPM
- MySQL 8.0+ atau MariaDB 10.6+
- Git

### Langkah-langkah Umum
1. Clone repositori:
   ```bash
   git clone https://github.com/risunCode/SIBARAKU-Laravel.git sibaraku
   cd sibaraku
   ```

2. Install dependensi PHP:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. Install dependensi Node.js:
   ```bash
   npm install
   npm run build
   ```

4. Salin file konfigurasi:
   ```bash
   cp .env.example.production .env
   ```

5. Generate kunci aplikasi:
   ```bash
   php artisan key:generate
   ```

6. Siapkan database dan jalankan migrasi:
   ```bash
   php artisan migrate --seed
   ```

7. Buat symlink untuk storage:
   ```bash
   php artisan storage:link
   ```

8. Optimasi untuk produksi:
   ```bash
   php artisan optimize:clear
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Deployment Lokal

### Development Server
```bash
# Terminal 1: Laravel Development Server
php artisan serve

# Terminal 2: Vite Hot Module Replacement (opsional untuk development)
npm run dev
```

### Akses
- URL: http://localhost:8000 atau http://127.0.0.1:8000
- Login Admin: admin@inventaris.com / panelsibaraku
- Login Staff: staff@inventaris.com / panelsibaraku
- ⚠️ Wajib setup security questions saat login pertama

---

## Deployment dengan ngrok

### Apa itu ngrok?
ngrok adalah layanan yang memungkinkan Anda mengekspos server lokal ke internet melalui URL publik sementara. Ini sangat berguna untuk:
- Demo aplikasi kepada klien
- Pengujian webhook
- Berbagi pengembangan dengan tim
- Pengujian dari perangkat mobile

### Persiapan

#### 1. Instal ngrok
```bash
# Windows (via Chocolatey)
choco install ngrok

# macOS (via Homebrew)
brew install ngrok

# Linux (via Snap)
sudo snap install ngrok

# Manual: Download dari https://ngrok.com/download
```

#### 2. Daftar Akun dan Setup Auth Token
1. Daftar akun gratis di [ngrok.com](https://ngrok.com)
2. Dapatkan auth token dari dashboard
3. Konfigurasi token:
   ```bash
   ngrok config add-authtoken YOUR_AUTH_TOKEN
   ```

### Langkah-langkah Deployment

#### 1. Jalankan Server Laravel Dengan Binding ke Semua IP
```bash
# Penting: Host 0.0.0.0 untuk mengizinkan koneksi dari semua interface
php artisan serve --host=0.0.0.0 --port=8000
```

#### 2. Di Terminal Terpisah, Jalankan ngrok
```bash
ngrok http 8000
```

#### 3. Perhatikan URL ngrok
```
Forwarding https://abc123def456.ngrok-free.app -> http://localhost:8000
```

#### 4. Update File `.env`
Edit file `.env` dengan URL ngrok:
```
APP_URL=https://abc123def456.ngrok-free.app
ASSET_URL=https://abc123def456.ngrok-free.app
SESSION_DOMAIN=.ngrok-free.app
SANCTUM_STATEFUL_DOMAINS=abc123def456.ngrok-free.app
```

#### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 6. Akses Via URL ngrok
Gunakan URL https://abc123def456.ngrok-free.app untuk mengakses aplikasi dari manapun.

### Keterbatasan ngrok Gratis
- URL berubah setiap kali restart tunnel
- Bandwidth dan koneksi terbatas
- Tidak ada subdomain kustom

### URL Permanen (Alternatif)
Untuk URL permanen, pertimbangkan:
1. **ngrok Pro**: Mendapatkan subdomain tetap (berbayar)
2. **Cloudflare Tunnel**: Alternatif gratis yang lebih stabil
3. **Serveo**: Layanan gratis alternatif ngrok (serveo.net)

### Penggunaan untuk Production
ngrok TIDAK DIREKOMENDASIKAN untuk penggunaan produksi, hanya untuk:
- Development
- Demo
- Pengujian
- Berbagi prototype

---

## Deployment dengan Cloudflare Tunnel

### Apa itu Cloudflare Tunnel?
Cloudflare Tunnel (sebelumnya Argo Tunnel) adalah layanan **GRATIS** dari Cloudflare yang memungkinkan Anda mengekspos server lokal ke internet dengan URL publik. Lebih stabil dan aman dibanding ngrok.

### Kelebihan Cloudflare Tunnel
- ✅ **Gratis** tanpa batasan bandwidth
- ✅ **URL stabil** (tidak berubah setiap restart)
- ✅ **HTTPS otomatis** dengan sertifikat Cloudflare
- ✅ **Keamanan tinggi** dengan DDoS protection
- ✅ **Tidak perlu registrasi** untuk quick tunnel

### Persiapan

#### 1. Install cloudflared
```bash
# Windows (via Chocolatey)
choco install cloudflared

# Windows (via Winget)
winget install --id Cloudflare.cloudflared

# macOS (via Homebrew)
brew install cloudflared

# Linux (Debian/Ubuntu)
curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
sudo dpkg -i cloudflared.deb

# Manual: Download dari https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/install-and-setup/installation/
```

### Quick Tunnel (Tanpa Akun - Paling Mudah)

#### 1. Jalankan Server Laravel
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

#### 2. Di Terminal Terpisah, Jalankan Cloudflare Tunnel
```bash
cloudflared tunnel --url http://localhost:8000
```

#### 3. Perhatikan URL yang Diberikan
```
INF +--------------------------------------------------------------------------------------------+
INF |  Your quick Tunnel has been created! Visit it at (it may take some time to be reachable): |
INF |  https://random-words-here.trycloudflare.com                                               |
INF +--------------------------------------------------------------------------------------------+
```

#### 4. Update File `.env`
```env
# [CLOUDFLARE] Mode
APP_ENV=local
APP_DEBUG=false
APP_URL=https://random-words-here.trycloudflare.com
ASSET_URL=https://random-words-here.trycloudflare.com
```

#### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Tunnel Permanen (Dengan Akun Cloudflare)

Untuk URL yang tidak berubah:

1. Login ke Cloudflare Zero Trust Dashboard
2. Buat tunnel baru di Access > Tunnels
3. Install connector dan konfigurasi
4. Dapatkan subdomain permanen seperti `sibaraku.yourdomain.com`

### Tips Cloudflare Tunnel
- **Quick tunnel** cocok untuk demo dan testing cepat
- **Named tunnel** cocok untuk environment staging
- URL quick tunnel berubah setiap restart, tapi lebih stabil dari ngrok
- Tidak ada batasan koneksi atau bandwidth

---

## Shared Hosting

### Persiapan File
1. Upload semua file ke server hosting
2. Arahkan document root ke folder `public/`
3. Pastikan file `.htaccess` sudah ada di folder `public/`

### Konfigurasi Database
1. Buat database baru melalui cPanel/phpMyAdmin
2. Update kredensial database di file `.env`

### File Permission
```bash
chmod -R 755 storage bootstrap/cache
```

### Contoh Konfigurasi .htaccess (Root Folder)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## VPS/Dedicated Server

### Menggunakan Nginx
```nginx
server {
    listen 80;
    server_name inventaris.your-domain.com;
    root /var/www/sibaraku/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### HTTPS dengan Let's Encrypt
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d inventaris.your-domain.com
```

### Supervisor untuk Queue (Opsional)
```
[program:sibaraku-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sibaraku/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sibaraku/storage/logs/worker.log
stopwaitsecs=3600
```

---

## Docker

### Menggunakan Docker Compose
File `docker-compose.yml` disediakan di root proyek. Untuk menjalankan:

```bash
docker-compose up -d
```

### Environment Docker
Gunakan file `.env.docker` yang disediakan sebagai template.

---

## Konfigurasi Tambahan

### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Optimize Images
```bash
# Install Image Optimizer
npm install -g @squoosh/cli

# Optimize images
php artisan optimize:images
```

### Scheduled Tasks
Tambahkan ke crontab:
```
* * * * * cd /path/to/sibaraku && php artisan schedule:run >> /dev/null 2>&1
```

---

## Checklist Deployment

- [ ] File `.env` dengan konfigurasi produksi
- [ ] Dependencies terinstal (`composer install`, `npm install`)
- [ ] Assets terbangun (`npm run build`)
- [ ] Key aplikasi ter-generate (`php artisan key:generate`)
- [ ] Migrasi database selesai (`php artisan migrate --seed`)
- [ ] Symlink storage dibuat (`php artisan storage:link`)
- [ ] Hak akses folder benar (755 untuk folder, 644 untuk file)
- [ ] Caching dioptimasi (`php artisan optimize`)
- [ ] HTTPS dikonfigurasi
- [ ] Server di-restart
- [ ] Backup database terakhir dibuat

---

## Pemecahan Masalah

### Halaman 500 Internal Server Error
- Periksa file `storage/logs/laravel.log`
- Pastikan hak akses folder benar
- Coba `php artisan optimize:clear`

### Masalah CSS/JS Tidak Muncul
- Pastikan `APP_URL` dan `ASSET_URL` benar
- Jalankan `npm run build` ulang
- Clear cache browser

### Database Connection Error
- Periksa kredensial database di `.env`
- Pastikan user database memiliki hak akses yang tepat

### Koneksi Terputus Pada ngrok
- Pastikan tunnel berjalan dengan benar
- Periksa batas penggunaan akun gratis
- Restart ngrok dengan perintah `ngrok http 8000`

### Performa Lambat
- Aktifkan cache di `.env`:
  ```
  CACHE_DRIVER=redis
  QUEUE_CONNECTION=redis
  SESSION_DRIVER=redis
  ```
- Optimasi database dengan index

---

## Support

Jika mengalami masalah dengan deployment, hubungi tim support:
- Email: support@sibaraku.example.com
- Issues: [GitHub Issues](https://github.com/risunCode/SIBARAKU-Laravel/issues)

---

*Dokumen ini terakhir diperbarui pada: November 30, 2025*
