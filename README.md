# SIBARAKU - Sistem Inventaris Barang Kabupaten Kubu Raya

![Laravel](https://img.shields.io/badge/Laravel-12.40.1-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-purple?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-GPL--3.0-blue?style=flat-square)
![Version](https://img.shields.io/badge/Version-1.1.1--public-green?style=flat-square)

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
**Login:** `admin@inventaris.com` / `panelsibaraku`

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

| Dashboard | Detail Barang |
|-----------|---------------|
| <img width="400" alt="Dashboard" src="https://github.com/user-attachments/assets/aa9c0f47-6dc0-4851-95fa-5d45c9869b03" /> | <img width="400" alt="Detail" src="https://github.com/user-attachments/assets/8bd6e9c0-1fe2-4bff-9e04-620d8c5b4319" /> |

| About | Dark Mode |
|-------|-----------|
| <img width="400" alt="About" src="https://github.com/user-attachments/assets/a6edcd66-32ee-4e1c-882f-9f03668aa37f" /> | <img width="400" alt="Dark Mode" src="https://github.com/user-attachments/assets/a078aa43-03a6-4989-af75-73a485119efd" /> |

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
