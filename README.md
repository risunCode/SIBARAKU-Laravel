# SIBARAKU - Sistem Inventaris Barang Kabupaten Kubu Raya

![Laravel](https://img.shields.io/badge/Laravel-12.40.1-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-purple?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-GPL--3.0-blue?style=flat-square)
![Version](https://img.shields.io/badge/Version-1.2.0--stable-green?style=flat-square)

Sistem manajemen inventaris barang berbasis web untuk instansi pemerintah, BUMN/BUMD, dan perusahaan swasta. Dibangun dengan Laravel 12.

---

## ⚡ Quick Start

```bash
git clone https://github.com/risunCode/SIBARAKU-Laravel.git sibaraku
cd sibaraku
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
npm run build && php artisan serve
```

**Akses:** http://localhost:8000  
**Login:**
- Admin: `admin@inventaris.com` / `panelsibaraku`
- Staff: `staff@inventaris.com` / `panelsibaraku`

> 📖 Panduan lengkap: [INSTALLATION.md](INSTALLATION.md)

---

## ✨ Fitur Utama

| Modul | Deskripsi |
|-------|-----------|
| **Dashboard** | Visualisasi real-time dengan Chart.js |
| **Barang** | CRUD lengkap dengan galeri gambar |
| **Kategori & Lokasi** | Manajemen data master |
| **Transfer** | Mutasi barang dengan workflow approval |
| **Pemeliharaan** | Jadwal dan log perawatan |
| **Penghapusan** | Disposal dengan sistem approval |
| **Laporan PDF** | 7 template laporan siap cetak |
| **Multi-role** | Admin & Staff dengan permission berbeda |
| **Dark Mode** | Tema gelap untuk kenyamanan |

---

## 📸 Tangkapan Layar

<details open>
<summary><strong>🖥️ Desktop View</strong></summary>
<br>

| | |
|:---:|:---:|
| ![Login Page](https://github.com/user-attachments/assets/98e34d62-83b5-45b2-b674-e768ae1a782f) | ![First Login](https://github.com/user-attachments/assets/d8a7482a-276e-4bfe-bc0a-b926dab7fd72) |
| **Login Page** | **First Login Setup** |
| ![Dashboard Light](https://github.com/user-attachments/assets/97593792-a5c1-45a4-a643-5fde40581f67) | ![Dashboard Dark](https://github.com/user-attachments/assets/3f7ecc14-9f20-4d2e-882f-363e19ec9fdf) |
| **Dashboard (Light)** | **Dashboard (Dark)** |
| ![Inventaris Barang](https://github.com/user-attachments/assets/767e9bcc-d776-49ab-9bc4-77508bb5edde) | ![Kategori](https://github.com/user-attachments/assets/702d83f7-08d7-498e-b571-82f61c3972e5) |
| **Inventaris Barang** | **Kategori** |
| ![Lokasi](https://github.com/user-attachments/assets/e80f6a22-744c-491f-b414-05a48d1bc51a) | ![Profile](https://github.com/user-attachments/assets/f183ef6f-291b-4fea-bf59-24d17babd9a3) |
| **Lokasi** | **Profile** |
| ![About Light](https://github.com/user-attachments/assets/cbda2859-0b2d-405f-87e5-a74d6046df82) | ![About Dark](https://github.com/user-attachments/assets/948dea1e-c464-4b79-81c1-70cdc19712f9) |
| **About (Light)** | **About (Dark)** |

</details>

---

## 🛠️ Teknologi

| Backend | Frontend | Tools |
|---------|----------|-------|
| Laravel 12.40.1 | TailwindCSS 4.0 | Vite 7.0 |
| PHP 8.3 | Alpine.js 3.15 | DomPDF 3.1 |
| MySQL/SQLite | Chart.js 4.x | Spatie Permission |

---

## 📊 Struktur Database

<img width="800" alt="ERD SIBARAKU" src="https://github.com/user-attachments/assets/94ea2684-844c-4374-a587-959d1bdb57aa" />

**17 Tabel:** users, categories, locations, commodities, commodity_images, transfers, maintenances, disposals, activity_logs, notifications, referral_codes, dll.

> 📁 SQL Schema: `database/sibaraku-full.sql`

---

## 📚 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [INSTALLATION.md](INSTALLATION.md) | Panduan instalasi lengkap |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Deploy ke produksi (ngrok, VPS, shared hosting) |
| [CHANGELOG.md](CHANGELOG.md) | Riwayat perubahan |
| [ROUTING.md](ROUTING.md) | Dokumentasi API & routes |

---

## 📋 TODO

- [ ] **PDF Export Enhancement** - Meningkatkan kualitas tampilan PDF export

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [GPL-3.0 License](LICENSE).

---

## ℹ️ Catatan Nama

**SIBARAKU** (Sistem Inventaris Barang Kubu Raya) adalah nama terbaru dari sistem ini. Sebelumnya bernama SIBARANG, diubah untuk menghindari konflik dengan proyek lain yang sudah ada.

---

**Dikembangkan untuk Kabupaten Kubu Raya** 🏛️
