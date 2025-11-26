# 🏢 Sistem Inventaris Barang - Kabupaten Kubu Raya

**Version: 0.0.1-beta** 🎉

Sistem manajemen inventaris barang yang komprehensif untuk pemerintah daerah, dibangun dengan Laravel 12 dan teknologi modern.

## 🚀 Features Utama

### 📊 Dashboard & Analytics
- Dashboard real-time dengan Chart.js
- Statistik kondisi barang (Donut chart)
- Distribusi per kategori (Bar chart)  
- Alerts untuk approval pending
- Sticky navigation untuk UX yang lebih baik

### 🗂️ Master Data Management
- **Categories**: CRUD dengan modal edit/delete + SweetAlert
- **Locations**: CRUD dengan modal edit/delete + SweetAlert  
- **Commodities**: CRUD lengkap dengan gallery preview
- Image gallery dengan zoom, drag, dan navigasi

### 💼 Transaction Management
- **Transfers**: Workflow approval dengan status tracking
- **Maintenance**: Scheduling dan log pemeliharaan
- **Disposals**: Proses penghapusan barang dengan approval

### 👥 User Management  
- **Role-based Access Control (RBAC)** dengan Spatie Laravel Permission
- **Modal-based operations** untuk create/edit users
- **Referral Code System** untuk registrasi user baru
- User details dengan wide layout dan stats

### 📝 Reporting System
- Multiple report types (Inventory, By Category, By Location, dll)
- **PDF Export** dengan custom styling
- Modern card-based report selection interface
- Print-friendly layouts

### 🔔 Notification & Activity
- Real-time notification system
- **Activity logging** untuk audit trail
- Notification bell dengan counter
- Activity exclusion untuk login events

### 🎨 UI/UX Enhancements
- **CSS Variables theming** untuk konsistensi
- **SweetAlert integration** untuk feedback yang lebih baik
- **Modal system** untuk operasi CRUD
- **Gallery lightbox** untuk preview gambar
- Responsive design untuk semua device sizes
- Enhanced error handling (development vs production)

## 🛠️ Technical Stack

- **Laravel**: 12.40.1
- **PHP**: 8.3.23
- **Database**: MySQL/SQLite  
- **Frontend**: Tailwind CSS + Alpine.js + Chart.js
- **Permissions**: Spatie Laravel Permission
- **PDF**: DomPDF
- **Notifications**: Laravel native notifications

## 📱 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 🏷️ Version History

### v0.0.1-beta (Current)
- ✅ Complete inventory management system
- ✅ All major features implemented and tested
- ✅ Modal-based CRUD operations
- ✅ Referral code system
- ✅ Enhanced UI/UX with charts and galleries
- ✅ Comprehensive reporting system

## 🚧 Roadmap

- [ ] API endpoints for mobile app
- [ ] Advanced reporting with filters
- [ ] Bulk operations for commodities
- [ ] Email notifications
- [ ] Advanced user roles and permissions

---

**Developed for Kabupaten Kubu Raya** 🏛️