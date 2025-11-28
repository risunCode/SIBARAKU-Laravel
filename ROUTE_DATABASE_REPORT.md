# 📋 ROUTE & DATABASE CONSISTENCY REPORT
**SIBARANG - Sistem Inventaris Barang**  
*Generated: November 28, 2025*

---

## 📊 SUMMARY

| Metric | Count |
|--------|-------|
| Total Routes | 91 |
| Total Models | 10 |
| Total Migrations | 18 |
| Issues Fixed | 10 ✅ |
| Remaining (Optional) | 3 🟡 |

---

## 🔄 COMPLETE ROUTE LIST

### 🚪 GUEST ROUTES (Belum Login)

| Method | URI | Route Name | Controller |
|--------|-----|------------|------------|
| GET | `/` | - | Redirect to `auth` |
| GET | `/auth` | `auth` | `AuthenticatedSessionController@index` |
| GET | `/login` | `login` | Redirect to `auth` |
| POST | `/login` | - | `AuthenticatedSessionController@store` |
| POST | `/register` | `register` | `RegisterController@store` |
| GET | `/api/validate-referral` | - | `RegisterController@validateReferral` |
| GET | `/forgot-password` | `password.request` | `PasswordResetController@create` |
| POST | `/forgot-password` | `password.email` | `PasswordResetController@verifyEmail` |
| GET | `/security-questions/{token}` | `password.security` | `PasswordResetController@showSecurityQuestions` |
| POST | `/security-questions/{token}` | - | `PasswordResetController@verifySecurityQuestions` |
| GET | `/reset-password/{token}` | `password.reset` | `PasswordResetController@showResetForm` |
| POST | `/reset-password` | `password.update` | `PasswordResetController@reset` |

---

### 🔐 AUTHENTICATED ROUTES (Sudah Login)

#### 📍 ROOT LEVEL

| Method | URI | Route Name | Controller |
|--------|-----|------------|------------|
| POST | `/logout` | `logout` | `AuthenticatedSessionController@destroy` |
| GET | `/dashboard` | `dashboard` | `DashboardController@index` |
| GET | `/about` | `about` | View: `about` |
| GET | `/security/setup` | `security.setup` | `RegisterController@showSetupSecurity` |
| POST | `/security/setup` | `security.store` | `RegisterController@storeSetupSecurity` |

---

#### 👤 PROFILE (`/profile`)

| Method | URI | Route Name | Controller |
|--------|-----|------------|------------|
| GET | `/profile` | `profile.edit` | `ProfileController@edit` |
| PATCH | `/profile` | `profile.update` | `ProfileController@update` |
| PUT | `/profile/password` | `profile.password` | `ProfileController@updatePassword` |
| PUT | `/profile/security` | `profile.security` | `ProfileController@updateSecurity` |

---

#### 🔔 NOTIFICATIONS (`/notifications`)

| Method | URI | Route Name | Controller |
|--------|-----|------------|------------|
| GET | `/notifications` | `notifications.index` | `NotificationController@index` |
| POST | `/notifications/mark-all-read` | `notifications.mark-all-read` | `NotificationController@markAllRead` |
| POST | `/notifications/{notification}/read` | `notifications.read` | `NotificationController@markRead` |

---

### 📦 MASTER DATA (`/master`)

#### 📋 CATEGORIES (`/master/categories`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/master/categories` | `categories.index` | `CategoryController@index` | `categories.view` |
| POST | `/master/categories` | `categories.store` | `CategoryController@store` | `categories.create` |
| GET | `/master/categories/create` | `categories.create` | `CategoryController@create` | `categories.create` |
| GET | `/master/categories/{category}/edit` | `categories.edit` | `CategoryController@edit` | `categories.edit` |
| PUT/PATCH | `/master/categories/{category}` | `categories.update` | `CategoryController@update` | `categories.edit` |
| DELETE | `/master/categories/{category}` | `categories.destroy` | `CategoryController@destroy` | `categories.delete` |

**Database Table:** `categories`  
**Model:** `Category`

---

#### 📍 LOCATIONS (`/master/locations`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/master/locations` | `locations.index` | `LocationController@index` | `locations.view` |
| POST | `/master/locations` | `locations.store` | `LocationController@store` | `locations.create` |
| GET | `/master/locations/create` | `locations.create` | `LocationController@create` | `locations.create` |
| GET | `/master/locations/{location}/edit` | `locations.edit` | `LocationController@edit` | `locations.edit` |
| PUT/PATCH | `/master/locations/{location}` | `locations.update` | `LocationController@update` | `locations.edit` |
| DELETE | `/master/locations/{location}` | `locations.destroy` | `LocationController@destroy` | `locations.delete` |

**Database Table:** `locations`  
**Model:** `Location`

---

#### 📦 COMMODITIES (`/master/commodities`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/master/commodities` | `commodities.index` | `CommodityController@index` | `commodities.view` |
| POST | `/master/commodities` | `commodities.store` | `CommodityController@store` | `commodities.create` |
| GET | `/master/commodities/create` | `commodities.create` | `CommodityController@create` | `commodities.create` |
| GET | `/master/commodities/{commodity}` | `commodities.show` | `CommodityController@show` | `commodities.view` |
| GET | `/master/commodities/{commodity}/edit` | `commodities.edit` | `CommodityController@edit` | `commodities.edit` |
| PUT/PATCH | `/master/commodities/{commodity}` | `commodities.update` | `CommodityController@update` | `commodities.edit` |
| DELETE | `/master/commodities/{commodity}` | `commodities.destroy` | `CommodityController@destroy` | `commodities.delete` |
| GET | `/master/commodities/export` | `commodities.export` | `CommodityController@export` | `commodities.export` |
| POST | `/master/commodities/import` | `commodities.import` | `CommodityController@import` | `commodities.import` |

**Database Table:** `commodities`  
**Model:** `Commodity`  
**Related Table:** `commodity_images`

---

### 💼 TRANSAKSI (`/transaksi`)

#### 🔄 TRANSFERS (`/transaksi/transfers`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/transaksi/transfers` | `transfers.index` | `TransferController@index` | `transfers.view` |
| POST | `/transaksi/transfers` | `transfers.store` | `TransferController@store` | `transfers.create` |
| GET | `/transaksi/transfers/create` | `transfers.create` | `TransferController@create` | `transfers.create` |
| GET | `/transaksi/transfers/{transfer}` | `transfers.show` | `TransferController@show` | `transfers.view` |
| DELETE | `/transaksi/transfers/{transfer}` | `transfers.destroy` | `TransferController@destroy` | `transfers.delete` |
| POST | `/transaksi/transfers/{transfer}/approve` | `transfers.approve` | `TransferController@approve` | `transfers.approve` |
| POST | `/transaksi/transfers/{transfer}/reject` | `transfers.reject` | `TransferController@reject` | `transfers.approve` |
| POST | `/transaksi/transfers/{transfer}/complete` | `transfers.complete` | `TransferController@complete` | `transfers.approve` |

**Database Table:** `transfers`  
**Model:** `Transfer`

---

#### 🔧 MAINTENANCE (`/transaksi/maintenance`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/transaksi/maintenance` | `maintenance.index` | `MaintenanceController@index` | `maintenance.view` |
| POST | `/transaksi/maintenance` | `maintenance.store` | `MaintenanceController@store` | `maintenance.create` |
| GET | `/transaksi/maintenance/create` | `maintenance.create` | `MaintenanceController@create` | `maintenance.create` |
| GET | `/transaksi/maintenance/{maintenance}` | `maintenance.show` | `MaintenanceController@show` | `maintenance.view` |
| GET | `/transaksi/maintenance/{maintenance}/edit` | `maintenance.edit` | `MaintenanceController@edit` | `maintenance.edit` |
| PUT/PATCH | `/transaksi/maintenance/{maintenance}` | `maintenance.update` | `MaintenanceController@update` | `maintenance.edit` |
| DELETE | `/transaksi/maintenance/{maintenance}` | `maintenance.destroy` | `MaintenanceController@destroy` | `maintenance.delete` |

**Database Table:** `maintenance_logs`  
**Model:** `MaintenanceLog`  
*Note: Naming mismatch - kept as-is (working)*

---

#### 🗑️ DISPOSALS (`/transaksi/disposals`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/transaksi/disposals` | `disposals.index` | `DisposalController@index` | `disposals.view` |
| POST | `/transaksi/disposals` | `disposals.store` | `DisposalController@store` | `disposals.create` |
| GET | `/transaksi/disposals/create` | `disposals.create` | `DisposalController@create` | `disposals.create` |
| GET | `/transaksi/disposals/{disposal}` | `disposals.show` | `DisposalController@show` | `disposals.view` |
| DELETE | `/transaksi/disposals/{disposal}` | `disposals.destroy` | `DisposalController@destroy` | `disposals.delete` |
| POST | `/transaksi/disposals/{disposal}/approve` | `disposals.approve` | `DisposalController@approve` | `disposals.approve` |
| POST | `/transaksi/disposals/{disposal}/reject` | `disposals.reject` | `DisposalController@reject` | `disposals.approve` |

**Database Table:** `disposals`  
**Model:** `Disposal`

---

### 📊 LAPORAN (`/laporan`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/laporan` | `reports.index` | `ReportController@index` | `reports.view` |
| GET | `/laporan/inventory` | `reports.inventory` | `ReportController@inventory` | `reports.view` |
| GET | `/laporan/by-category` | `reports.by-category` | `ReportController@byCategory` | `reports.view` |
| GET | `/laporan/by-location` | `reports.by-location` | `ReportController@byLocation` | `reports.view` |
| GET | `/laporan/by-condition` | `reports.by-condition` | `ReportController@byCondition` | `reports.view` |
| GET | `/laporan/transfers` | `reports.transfers` | `ReportController@transfers` | `reports.view` |
| GET | `/laporan/disposals` | `reports.disposals` | `ReportController@disposals` | `reports.view` |
| GET | `/laporan/maintenance` | `reports.maintenance` | `ReportController@maintenance` | `reports.view` |
| GET | `/laporan/kib` | `reports.kib` | `ReportController@kib` | `reports.view` |

---

### 👥 ADMIN (`/admin`)

#### 👤 USERS (`/admin/users`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/admin/users` | `users.index` | `UserController@index` | `users.view` |
| POST | `/admin/users` | `users.store` | `UserController@store` | `users.create` |
| GET | `/admin/users/create` | `users.create` | `UserController@create` | `users.create` |
| GET | `/admin/users/{user}` | `users.show` | `UserController@show` | `users.view` |
| GET | `/admin/users/{user}/edit` | `users.edit` | `UserController@edit` | `users.edit` |
| PUT/PATCH | `/admin/users/{user}` | `users.update` | `UserController@update` | `users.edit` |
| DELETE | `/admin/users/{user}` | `users.destroy` | `UserController@destroy` | `users.delete` |

**Database Table:** `users`  
**Model:** `User`

---

#### 🎫 REFERRAL CODES (`/admin/referral-codes`)

| Method | URI | Route Name | Controller | Permission |
|--------|-----|------------|------------|------------|
| GET | `/admin/referral-codes` | `referral-codes.index` | `ReferralCodeController@index` | `referral-codes.manage` |
| POST | `/admin/referral-codes` | `referral-codes.store` | `ReferralCodeController@store` | `referral-codes.manage` |
| GET | `/admin/referral-codes/generate` | `referral-codes.generate` | `ReferralCodeController@generate` | `referral-codes.manage` |
| PUT | `/admin/referral-codes/{referralCode}` | `referral-codes.update` | `ReferralCodeController@update` | `referral-codes.manage` |
| POST | `/admin/referral-codes/{referralCode}/toggle` | `referral-codes.toggle` | `ReferralCodeController@toggle` | `referral-codes.manage` |
| DELETE | `/admin/referral-codes/{referralCode}` | `referral-codes.destroy` | `ReferralCodeController@destroy` | `referral-codes.manage` |

**Database Table:** `referral_codes`  
**Model:** `ReferralCode`  
**Related Table:** `referral_code_usage`

---

## 🗄️ DATABASE TABLES

| # | Table Name | Model | Migration | Route Prefix |
|---|------------|-------|-----------|--------------|
| 1 | `users` | `User` | `create_users_table` | `/admin/users` |
| 2 | `categories` | `Category` | `create_categories_table` | `/master/categories` |
| 3 | `locations` | `Location` | `create_locations_table` | `/master/locations` |
| 4 | `commodities` | `Commodity` | `create_commodities_table` | `/master/commodities` |
| 5 | `commodity_images` | `CommodityImage` | `create_commodity_images_table` | - (sub-resource) |
| 6 | `transfers` | `Transfer` | `create_transfers_table` | `/transaksi/transfers` |
| 7 | `maintenance_logs` | `MaintenanceLog` | `create_maintenance_logs_table` | `/transaksi/maintenance` |
| 8 | `disposals` | `Disposal` | `create_disposals_table` | `/transaksi/disposals` |
| 9 | `activity_logs` | `ActivityLog` | `create_activity_logs_table` | - (internal) |
| 10 | `notifications` | - (Laravel default) | `create_notifications_table` | `/notifications` |
| 11 | `referral_codes` | `ReferralCode` | `create_referral_codes_table` | `/admin/referral-codes` |
| 12 | `referral_code_usage` | - | `create_referral_code_usage_table` | - (pivot) |
| 13 | `cache` | - | `create_cache_table` | - (system) |
| 14 | `jobs` | - | `create_jobs_table` | - (system) |

---

## ✅ ALL ISSUES RESOLVED!

### 1. ✅ Maintenance Table Naming - **FIXED**

| Aspect | Before | After |
|--------|--------|-------|
| Table | `maintenance_logs` | `maintenances` |
| Model | `MaintenanceLog` | `Maintenance` |
| Route | `/transaksi/maintenance` | `/transaksi/maintenance` |

---

### 2. ✅ Show Routes - **FIXED**

| Resource | Status |
|----------|--------|
| categories | ✅ Added `/kategori/{category}` |
| locations | ✅ Added `/lokasi/{location}` |

---

### 3. ✅ URL Rename to Indonesian - **FIXED**

| Old URL | New URL (Indonesian) |
|---------|---------------------|
| `/master/commodities` | `/barang` |
| `/master/categories` | `/kategori` |
| `/master/locations` | `/lokasi` |
| `/transaksi/transfers` | `/mutasi` |
| `/transaksi/maintenance` | `/pemeliharaan` |
| `/transaksi/disposals` | `/penghapusan` |
| `/admin/users` | `/pengguna` |
| `/admin/referral-codes` | `/kode-referral` |
| `/laporan/inventory` | `/laporan/inventaris` |
| `/laporan/by-category` | `/laporan/per-kategori` |
| `/laporan/by-location` | `/laporan/per-lokasi` |
| `/laporan/by-condition` | `/laporan/per-kondisi` |

---

## ✅ COMPLETED FIXES (Nov 28, 2025)

| # | Issue | Fix Applied |
|---|-------|-------------|
| 1 | Missing `commodities.import` permission | Added middleware |
| 2 | Missing `transfers.delete` permission | Added middleware |
| 3 | Missing `disposals.delete` permission | Added middleware |
| 4 | ReferralCode permission naming | Changed to `referral-codes.manage` |
| 5 | DashboardController no middleware | Added `dashboard.view` |
| 6 | NotificationController no middleware | Added `notifications.view` |
| 7 | Notification ownership check | Added `notifiable_id` check |
| 8 | Export/Import non-REST URLs | Changed to `/commodities/export` & `/import` |
| 9 | Missing rate limiting | Added `throttle:5,1` & `throttle:3,1` |
| 10 | Route ordering conflict | Fixed `referral-codes/generate` position |

---

## 🔧 PERMISSION MAPPING

| Permission | Routes Applied To |
|------------|-------------------|
| `categories.view` | `categories.index` |
| `categories.create` | `categories.create`, `categories.store` |
| `categories.edit` | `categories.edit`, `categories.update` |
| `categories.delete` | `categories.destroy` |
| `locations.view` | `locations.index` |
| `locations.create` | `locations.create`, `locations.store` |
| `locations.edit` | `locations.edit`, `locations.update` |
| `locations.delete` | `locations.destroy` |
| `commodities.view` | `commodities.index`, `commodities.show` |
| `commodities.create` | `commodities.create`, `commodities.store` |
| `commodities.edit` | `commodities.edit`, `commodities.update` |
| `commodities.delete` | `commodities.destroy` |
| `commodities.export` | `commodities.export` |
| `transfers.view` | `transfers.index`, `transfers.show` |
| `transfers.create` | `transfers.create`, `transfers.store` |
| `transfers.approve` | `transfers.approve`, `transfers.reject`, `transfers.complete` |
| `maintenance.view` | `maintenance.index`, `maintenance.show` |
| `maintenance.create` | `maintenance.create`, `maintenance.store` |
| `maintenance.edit` | `maintenance.edit`, `maintenance.update` |
| `maintenance.delete` | `maintenance.destroy` |
| `disposals.view` | `disposals.index`, `disposals.show` |
| `disposals.create` | `disposals.create`, `disposals.store` |
| `disposals.approve` | `disposals.approve`, `disposals.reject` |
| `reports.view` | All report routes |
| `users.view` | `users.index`, `users.show` |
| `users.create` | `users.create`, `users.store` |
| `users.edit` | `users.edit`, `users.update` |
| `users.delete` | `users.destroy` |
| `users.manage` | All referral-codes routes |

---

## 📊 FINAL SUMMARY

| Status | Count |
|--------|-------|
| ✅ Issues Fixed | **13** |
| 🟡 Remaining | **0** |
| 🔴 Critical Unfixed | **0** |

### 🎉 **ALL ISSUES RESOLVED!**

**Latest Changes (Nov 28, 2025):**
- ✅ Renamed `maintenance_logs` → `maintenances` table
- ✅ Renamed `MaintenanceLog` → `Maintenance` model  
- ✅ Added show routes for categories & locations
- ✅ Renamed ALL URLs to Indonesian

*Report updated: November 28, 2025*  
*Generated by Cascade AI Assistant*