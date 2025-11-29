# SIBARANG - Sistem Inventaris Barang

**Versi: v1.0.0**

Sistem manajemen inventaris barang untuk instansi pemerintah, BUMN/BUMD, dan perusahaan swasta. Dibangun dengan Laravel 12.

---

## Instalasi Cepat

### Mode Produksi
```bash
git clone https://github.com/risunCode/inventaris_barang_laravel.git your-inventory
cd your-inventory && composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
npm run build && php artisan serve
```

### Dengan Data Demo
```bash
php artisan db:seed --class="Database\MigrationsDemo\DemoSeeder"
```

**Data Produksi:**
- 1 User Admin (admin@inventaris.com / panelsibarang)
- 5 Kategori: ATK, ELK, KMP, TIK, PRT
- 5 Lokasi: GU, GB, RS, RD, RM  
- 3 Kode Referral: ADMIN2025, STAFF2025, DEMO2025

**Data Demo (Opsional):**
- 10 Lokasi tambahan
- 18 Sampel barang inventaris

### Mode Pengembangan
```bash
# Terminal 1 - Vite Dev Server
npm run dev

# Terminal 2 - Laravel Server
php artisan serve
```

**Akses:** http://127.0.0.1:8000  
**Login:** admin@inventaris.com / panelsibarang

---

## Daftar Isi

- [Instalasi Cepat](#instalasi-cepat)
- [Fitur Utama](#fitur-utama)
- [Tangkapan Layar](#tangkapan-layar)
- [Teknologi](#teknologi)
- [Panduan Instalasi](#panduan-instalasi)
- [Konfigurasi Produksi](#konfigurasi-produksi)
- [Pemecahan Masalah](#pemecahan-masalah)
- [Dukungan Browser](#dukungan-browser)
- [Riwayat Versi](#riwayat-versi)

---

## Fitur Utama

### Dashboard dan Analitik
- Dashboard real-time dengan Chart.js
- Statistik kondisi barang (Donut chart)
- Distribusi per kategori (Bar chart)  
- Notifikasi approval pending

### Pengelolaan Data Master
- **Kategori**: Operasi CRUD dengan modal dan notifikasi
- **Lokasi**: Pengelolaan lokasi penyimpanan barang
- **Barang**: CRUD lengkap dengan galeri gambar
- Galeri gambar dengan zoom dan navigasi

### Manajemen Transaksi
- **Transfer**: Workflow persetujuan dengan pelacakan status
- **Pemeliharaan**: Penjadwalan dan log perawatan barang
- **Penghapusan**: Proses disposal dengan sistem approval

### Pengelolaan Pengguna
- Role-based Access Control dengan Spatie Laravel Permission
- Sistem kode referral untuk registrasi
- Manajemen profil dengan fitur crop foto
- Pintasan keyboard untuk crop gambar

### Sistem Laporan
- Berbagai jenis laporan (Inventaris, Per Kategori, Per Lokasi)
- Ekspor PDF dengan styling kustom
- Layout ramah cetak

### Notifikasi dan Aktivitas
- Sistem notifikasi real-time
- Pencatatan aktivitas untuk audit trail

### Antarmuka Pengguna
- Theming dengan CSS Variables
- Integrasi SweetAlert untuk feedback
- Sistem modal untuk operasi CRUD
- Desain responsif untuk semua ukuran layar

---

## Tangkapan Layar

### Dashboard
Dashboard real-time dengan visualisasi data dan monitoring status barang.

<img width="1920" height="1080" alt="Dashboard SIBARANG" src="https://github.com/user-attachments/assets/aa9c0f47-6dc0-4851-95fa-5d45c9869b03" />

### Detail Barang
Interface detail barang dengan galeri dan informasi lengkap.

<img width="1920" height="1080" alt="Detail Barang SIBARANG" src="https://github.com/user-attachments/assets/8bd6e9c0-1fe2-4bff-9e04-620d8c5b4319" />

### Tentang Sistem
Halaman informasi sistem dan teknologi yang digunakan.

<img width="1920" height="1080" alt="About SIBARANG" src="https://github.com/user-attachments/assets/a6edcd66-32ee-4e1c-882f-9f03668aa37f" />

### About Dark Mode
Dark mode untuk meningkatkan kenyamanan pengguna di malam hari.

<img width="1920" height="1080" alt="About SIBARANG Dark Mode" src="https://github.com/user-attachments/assets/a078aa43-03a6-4989-af75-73a485119efd" />

---

## Teknologi

| Komponen | Versi |
|----------|-------|
| Laravel | 12.40.1 |
| PHP | 8.3.23 |
| Database | MySQL/SQLite |
| Frontend | Tailwind CSS, Alpine.js, Chart.js |
| Permission | Spatie Laravel Permission |
| PDF | DomPDF |

---

## Panduan Instalasi

### Prasyarat

- PHP 8.2+
- Composer 2.x
- Node.js 18+ dan NPM
- MySQL 8.0 atau MariaDB 10.6+
- Git

### Langkah 1: Clone Repository
```bash
git clone <repository-url>
cd Inventaris-barang-ferdi
```

### Langkah 2: Install Dependencies
```bash
composer install
npm install
```

### Langkah 3: Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:
```env
APP_NAME="SIBARANG - Sistem Inventaris Barang"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sibarang_inventaris
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Langkah 4: Setup Database
```bash
mysql -u root -p -e "CREATE DATABASE sibarang_inventaris"
php artisan migrate:fresh --seed
```

### Langkah 5: Storage Link
```bash
php artisan storage:link
```

### Langkah 6: Build dan Jalankan
```bash
npm run build
php artisan serve
```

**Akses:** http://127.0.0.1:8000

### Login Default
| Email | Password | Role |
|-------|----------|------|
| admin@inventaris.com | panelsibarang | Admin |

> Login pertama memerlukan pengaturan keamanan (tanggal lahir dan pertanyaan keamanan).

---

## Konfigurasi Produksi

### Build Assets
```bash
npm run build
```

### Konfigurasi Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### Optimasi Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Pengaturan PHP (php.ini)
```ini
upload_max_filesize = 10M
post_max_size = 50M
max_file_uploads = 20
memory_limit = 256M
max_execution_time = 300
```

### Permission File (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Konfigurasi Apache
```apache
<VirtualHost *:80>
    DocumentRoot /path/to/sibarang/public
    ServerName yourdomain.com
    
    <Directory /path/to/sibarang/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Konfigurasi Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/sibarang/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Hosting Jaringan Lokal

Untuk akses dari perangkat lain di jaringan lokal:

```bash
# Cari IP lokal (Windows)
ipconfig

# Jalankan server
php artisan serve --host=0.0.0.0 --port=8000

# Buka firewall (PowerShell Admin)
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=tcp localport=8000
```

Akses dari perangkat lain: `http://192.168.x.x:8000`

---

## Pemecahan Masalah

### Error 404 pada Routes
```bash
php artisan route:clear
php artisan config:clear
```

### Masalah Permission (Linux)
```bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
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

## Dukungan Browser

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## Riwayat Versi

### v1.0.0 (29 Nov 2025)

**Pembaruan Utama:**
- Perbaikan duplikasi pagination di 8 halaman
- Standardisasi empty state dengan component konsisten
- Mobile sidebar auto-close untuk navigasi lebih baik
- Toast notification responsif dengan close button
- Global keyboard shortcuts (Ctrl+K, Ctrl+N, ESC, Home)
- Back-to-top button dengan smooth scrolling
- Search input dengan tombol clear
- Optimasi lebar kolom tabel untuk readability
- Upgrade versi dari beta ke stable production

**Peningkatan UX:**
- Konsistensi desain di seluruh aplikasi
- Mobile responsiveness ditingkatkan
- Keyboard navigation untuk power users
- Accessibility improvements (ARIA labels)

Changelog lengkap: [CHANGELOG.md](CHANGELOG.md)

### v0.0.3-beta (29 Nov 2025)

**Pembaruan Utama:**
- Struktur database terpisah antara produksi dan demo
- Instalasi produksi hanya membuat user admin
- Sistem data demo opsional
- Perbaikan export PDF 404
- Fitur crop foto profil dengan keyboard shortcuts
- Perbaikan error 404 profil pengguna
- Peningkatan UI/UX secara keseluruhan

**Peningkatan Keamanan:**
- Instalasi produksi tidak menyertakan data demo
- Pengaturan keamanan wajib pada login pertama

Changelog lengkap: [CHANGELOG.md](CHANGELOG.md)

---

**Dikembangkan untuk Kabupaten Kubu Raya**