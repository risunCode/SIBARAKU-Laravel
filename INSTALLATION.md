# 📦 SIBARAKU - Panduan Instalasi

**Versi: v1.0.0-public**

---

## Prasyarat

- PHP 8.2+
- Composer 2.x
- Node.js 18+ dan NPM
- MySQL 8.0 atau MariaDB 10.6+
- Git

---

## Instalasi Langkah demi Langkah

### 1. Clone Repository
```bash
git clone https://github.com/risunCode/inventaris_barang_laravel.git sibaraku
cd sibaraku
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:
```env
APP_NAME="SIBARAKU - Sistem Inventaris Barang Kubu Raya"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sibaraku_inventaris
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Setup Database
```bash
mysql -u root -p -e "CREATE DATABASE sibaraku_inventaris"
php artisan migrate:fresh --seed
```

### 5. Storage Link
```bash
php artisan storage:link
```

### 6. Build dan Jalankan
```bash
npm run build
php artisan serve
```

**Akses:** http://127.0.0.1:8000

---

## Login Default

| Email | Password | Role |
|-------|----------|------|
| admin@inventaris.com | panelsibaraku | Admin |

> Login pertama memerlukan pengaturan keamanan (tanggal lahir dan pertanyaan keamanan).

---

## Mode Pengembangan

```bash
# Terminal 1 - Vite Dev Server (Hot Reload)
npm run dev

# Terminal 2 - Laravel Server
php artisan serve
```

---

## Dengan Data Demo

Untuk menambahkan data demo (barang sampel, lokasi tambahan):

```bash
php artisan db:seed --class="Database\MigrationsDemo\DemoSeeder"
```

**Data Demo:**
- 10 Lokasi tambahan
- 18 Sampel barang inventaris

---

## Akses dari Jaringan Lokal

Untuk akses dari perangkat lain di jaringan lokal:

```bash
# Cari IP lokal (Windows)
ipconfig

# Jalankan server dengan binding ke semua IP
php artisan serve --host=0.0.0.0 --port=8000

# Buka firewall (PowerShell Admin)
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=tcp localport=8000
```

Akses dari perangkat lain: `http://192.168.x.x:8000`

---

## Akses dari Luar (ngrok)

Untuk demo atau berbagi aplikasi sementara ke internet:

### 1. Install ngrok
```bash
# Windows (Chocolatey)
choco install ngrok

# macOS (Homebrew)
brew install ngrok

# Download: https://ngrok.com/download
```

### 2. Setup Auth Token
```bash
# Daftar gratis di https://ngrok.com lalu:
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

### 3. Jalankan
```bash
# Terminal 1 - Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - ngrok
ngrok http 8000
```

### 4. Update `.env`
```env
APP_URL=https://your-ngrok-url.ngrok-free.app
ASSET_URL=https://your-ngrok-url.ngrok-free.app
SESSION_DOMAIN=.ngrok-free.app
```

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

> ⚠️ URL ngrok gratis berubah setiap restart. Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk deployment permanen.

---

## Pemecahan Masalah

### Error 404 pada Routes
```bash
php artisan route:clear
php artisan config:clear
```

### Masalah Permission (Linux)
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Assets Tidak Termuat
```bash
npm run build
php artisan view:clear
```

### Error Koneksi Database
- Periksa kredensial database di `.env`
- Pastikan service MySQL berjalan
- Test koneksi: `php artisan tinker` lalu `DB::connection()->getPdo()`

---

## Dokumentasi Lainnya

- [README.md](README.md) - Overview aplikasi
- [DEPLOYMENT.md](DEPLOYMENT.md) - Panduan deployment produksi
- [CHANGELOG.md](CHANGELOG.md) - Riwayat perubahan
- [ROUTING.md](ROUTING.md) - Dokumentasi routing

---

*Untuk bantuan lebih lanjut, buka issue di GitHub.*
