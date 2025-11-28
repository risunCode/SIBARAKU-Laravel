# 📋 CHANGELOG - SIBARANG
**Sistem Inventaris Barang**

---

## [v0.0.5-beta] - 2025-11-28 (Production Ready)

### 🔒 Security Fixes

#### Permission Middleware Added
- **`CommodityController`** - Added `commodities.import` permission for import action
- **`TransferController`** - Added `transfers.delete` permission for destroy action
- **`DisposalController`** - Added `disposals.delete` permission for destroy action
- **`DashboardController`** - Added `HasMiddleware` with `dashboard.view` permission
- **`NotificationController`** - Added `HasMiddleware` with `notifications.view` permission

#### Authorization Improvements
- **`NotificationController@markRead`** - Added ownership check to prevent users from marking other users' notifications as read
- **`ReferralCodeController`** - Fixed permission naming from `users.manage` to `referral-codes.manage`

#### Rate Limiting
- Added `throttle:5,1` to export routes (5 requests per minute)
- Added `throttle:3,1` to import routes (3 requests per minute)

---

### 🗄️ Database Changes

#### Table Renamed
- `maintenance_logs` → `maintenances`
  - Created migration: `2025_11_28_094213_rename_maintenance_logs_to_maintenances.php`

#### Model Renamed
- `MaintenanceLog.php` → `Maintenance.php`
  - Updated all references in controllers, models, and views
  - Updated relationship name from `maintenanceLogs()` to `maintenances()`

---

### 🔗 Route Changes

#### URL Renamed to Indonesian
| Old URL | New URL |
|---------|---------|
| `/master/commodities` | `/barang` |
| `/master/commodities/export` | `/barang/ekspor` |
| `/master/commodities/import` | `/barang/impor` |
| `/master/categories` | `/kategori` |
| `/master/locations` | `/lokasi` |
| `/transaksi/transfers` | `/mutasi` |
| `/transaksi/transfers/{id}/approve` | `/mutasi/{id}/setujui` |
| `/transaksi/transfers/{id}/reject` | `/mutasi/{id}/tolak` |
| `/transaksi/transfers/{id}/complete` | `/mutasi/{id}/selesai` |
| `/transaksi/maintenance` | `/pemeliharaan` |
| `/transaksi/disposals` | `/penghapusan` |
| `/transaksi/disposals/{id}/approve` | `/penghapusan/{id}/setujui` |
| `/transaksi/disposals/{id}/reject` | `/penghapusan/{id}/tolak` |
| `/admin/users` | `/pengguna` |
| `/admin/referral-codes` | `/kode-referral` |
| `/laporan/inventory` | `/laporan/inventaris` |
| `/laporan/by-category` | `/laporan/per-kategori` |
| `/laporan/by-location` | `/laporan/per-lokasi` |
| `/laporan/by-condition` | `/laporan/per-kondisi` |
| `/laporan/transfers` | `/laporan/mutasi` |
| `/laporan/disposals` | `/laporan/penghapusan` |
| `/laporan/maintenance` | `/laporan/pemeliharaan` |

#### New Routes Added
- `GET /kategori/{category}` - Category detail page
- `GET /lokasi/{location}` - Location detail page

#### Route Ordering Fixed
- Moved `/kode-referral/generate` before parameter routes to prevent conflicts

---

### 🎨 Views Changes

#### New Views Created
- `resources/views/categories/show.blade.php` - Category detail view
- `resources/views/locations/show.blade.php` - Location detail view

#### Hardcoded URLs Fixed
| File | Old | New |
|------|-----|-----|
| `categories/index.blade.php` | `/master/categories/` | `/kategori/` |
| `locations/index.blade.php` | `/master/locations/` | `/lokasi/` |
| `users/index.blade.php` | `/admin/users/` | `/pengguna/` |
| `referral-codes/index.blade.php` | `/admin/referral-codes/` | `/kode-referral/` |

#### Variable Names Updated
- `$maintenanceLogs` → `$maintenances` in maintenance views

---

### 🔧 AppServiceProvider - New Gates

```php
// New permissions added
Gate::define('commodities.import', ...);
Gate::define('transfers.delete', ...);
Gate::define('disposals.delete', ...);
Gate::define('referral-codes.manage', ...);
Gate::define('dashboard.view', ...);
Gate::define('notifications.view', ...);
```

---

### 📁 Files Modified

#### Controllers
- `app/Http/Controllers/CommodityController.php`
- `app/Http/Controllers/TransferController.php`
- `app/Http/Controllers/DisposalController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/NotificationController.php`
- `app/Http/Controllers/ReferralCodeController.php`
- `app/Http/Controllers/MaintenanceController.php`
- `app/Http/Controllers/ReportController.php`
- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/LocationController.php`

#### Models
- `app/Models/Maintenance.php` (new, replaces MaintenanceLog)
- `app/Models/Commodity.php` (updated relationship)
- `app/Models/ActivityLog.php` (updated class mapping)

#### Routes
- `routes/web.php` (complete restructure)

#### Providers
- `app/Providers/AppServiceProvider.php` (new gates)

#### Views
- `resources/views/categories/index.blade.php`
- `resources/views/categories/show.blade.php` (new)
- `resources/views/locations/index.blade.php`
- `resources/views/locations/show.blade.php` (new)
- `resources/views/users/index.blade.php`
- `resources/views/referral-codes/index.blade.php`
- `resources/views/maintenance/index.blade.php`
- `resources/views/reports/maintenance.blade.php`

#### Migrations
- `database/migrations/2025_11_28_094213_rename_maintenance_logs_to_maintenances.php` (new)

#### Deleted Files
- `app/Models/MaintenanceLog.php`

---

### 📊 Summary

| Category | Count |
|----------|-------|
| Security fixes | 7 |
| Database changes | 1 |
| Route changes | 20+ |
| New views | 2 |
| Files modified | 20+ |
| Files deleted | 1 |

---

### ⚠️ Breaking Changes

1. **URL Structure** - All URLs have been changed to Indonesian
   - Bookmarks and external links will need to be updated
   - Route names remain unchanged, so `route()` helper calls still work

2. **Database Table** - `maintenance_logs` renamed to `maintenances`
   - Run `php artisan migrate` to apply changes

3. **Model Class** - `MaintenanceLog` renamed to `Maintenance`
   - Update any custom code referencing the old class name

---

### 🚀 Upgrade Instructions

```bash
# 1. Pull latest changes
git pull

# 2. Run migrations
php artisan migrate

# 3. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Verify routes
php artisan route:list
```

---

*Generated by Cascade AI Assistant - November 28, 2025*
