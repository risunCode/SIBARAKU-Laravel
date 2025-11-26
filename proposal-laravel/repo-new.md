# Aplikasi Inventaris Barang - Laravel 12

## Overview
Aplikasi inventaris barang berbasis Laravel 12 dengan arsitektur modular, clean code principles, dan scalable structure.

---

## Project Structure

```
inventaris-barang/
├── app/
│   ├── Console/
│   │   └── Commands/          # Custom artisan commands
│   ├── Events/                # Event classes
│   ├── Exceptions/            # Custom exception handlers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # API controllers
│   │   │   │   ├── V1/        # API version 1
│   │   │   │   │   ├── Auth/
│   │   │   │   │   ├── Inventory/
│   │   │   │   │   ├── Report/
│   │   │   │   │   └── Master/
│   │   │   └── Web/           # Web controllers
│   │   │       ├── Auth/
│   │   │       ├── Dashboard/
│   │   │       ├── Inventory/
│   │   │       ├── Report/
│   │   │       └── Master/
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   ├── CheckPermission.php
│   │   │   └── AuditLog.php
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   ├── Inventory/
│   │   │   ├── Report/
│   │   │   └── Master/
│   │   └── Resources/         # API resources (transformers)
│   │       ├── Inventory/
│   │       ├── Report/
│   │       └── Master/
│   ├── Jobs/                  # Queue jobs
│   │   ├── ExportInventoryReport.php
│   │   ├── SendLowStockAlert.php
│   │   └── ProcessBulkImport.php
│   ├── Listeners/             # Event listeners
│   ├── Mail/                  # Mailable classes
│   │   ├── LowStockNotification.php
│   │   └── InventoryReport.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Inventory/
│   │   │   ├── Item.php
│   │   │   ├── Category.php
│   │   │   ├── Unit.php
│   │   │   ├── Location.php
│   │   │   ├── Stock.php
│   │   │   ├── StockMovement.php
│   │   │   └── StockOpname.php
│   │   ├── Master/
│   │   │   ├── Supplier.php
│   │   │   ├── Customer.php
│   │   │   └── Warehouse.php
│   │   └── Transaction/
│   │       ├── PurchaseOrder.php
│   │       ├── PurchaseOrderDetail.php
│   │       ├── Receipt.php
│   │       ├── ReceiptDetail.php
│   │       ├── Issue.php
│   │       └── IssueDetail.php
│   ├── Notifications/         # Notification classes
│   │   ├── LowStockAlert.php
│   │   └── StockMovementAlert.php
│   ├── Observers/             # Model observers
│   │   ├── ItemObserver.php
│   │   └── StockObserver.php
│   ├── Policies/              # Authorization policies
│   │   ├── ItemPolicy.php
│   │   ├── StockPolicy.php
│   │   └── ReportPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   ├── RouteServiceProvider.php
│   │   └── RepositoryServiceProvider.php
│   ├── Repositories/          # Repository pattern
│   │   ├── Contracts/
│   │   │   ├── ItemRepositoryInterface.php
│   │   │   ├── StockRepositoryInterface.php
│   │   │   └── ReportRepositoryInterface.php
│   │   └── Eloquent/
│   │       ├── ItemRepository.php
│   │       ├── StockRepository.php
│   │       └── ReportRepository.php
│   ├── Services/              # Business logic layer
│   │   ├── InventoryService.php
│   │   ├── StockMovementService.php
│   │   ├── ReportService.php
│   │   ├── ExportService.php
│   │   └── ImportService.php
│   └── Traits/                # Reusable traits
│       ├── HasAuditLog.php
│       ├── HasUuid.php
│       └── Searchable.php
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── inventory.php         # Custom config for inventory
│   ├── filesystems.php
│   ├── mail.php
│   ├── queue.php
│   └── services.php
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── ItemFactory.php
│   │   └── StockFactory.php
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_roles_table.php
│   │   ├── 2024_01_01_000002_create_permissions_table.php
│   │   ├── 2024_01_01_000003_create_role_user_table.php
│   │   ├── 2024_01_01_000004_create_permission_role_table.php
│   │   └── [PENDING - Database structure from user]
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       ├── PermissionSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── UnitSeeder.php
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   ├── images/
│   │   └── vendor/
│   ├── exports/               # Temporary export files
│   └── index.php
│
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   └── components/
│   │       ├── datatable.js
│   │       ├── select2-init.js
│   │       ├── chart-init.js
│   │       └── scanner.js     # Barcode/QR scanner
│   ├── lang/
│   │   ├── en/
│   │   └── id/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   ├── navigation.blade.php
│       │   └── sidebar.blade.php
│       ├── components/
│       │   ├── alert.blade.php
│       │   ├── button.blade.php
│       │   ├── card.blade.php
│       │   ├── modal.blade.php
│       │   └── table.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── forgot-password.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── inventory/
│       │   ├── items/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── show.blade.php
│       │   ├── stock/
│       │   │   ├── index.blade.php
│       │   │   ├── movement.blade.php
│       │   │   └── opname.blade.php
│       │   ├── categories/
│       │   ├── units/
│       │   └── locations/
│       ├── transactions/
│       │   ├── purchase-orders/
│       │   ├── receipts/
│       │   └── issues/
│       ├── master/
│       │   ├── suppliers/
│       │   ├── customers/
│       │   └── warehouses/
│       ├── reports/
│       │   ├── stock-report.blade.php
│       │   ├── movement-report.blade.php
│       │   ├── valuation-report.blade.php
│       │   └── aging-report.blade.php
│       └── users/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── console.php
│   └── channels.php
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── items/         # Item images
│   │   │   ├── exports/       # Generated reports
│   │   │   └── imports/       # Import templates/files
│   │   └── private/
│   ├── framework/
│   ├── logs/
│   └── exports/
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── Inventory/
│   │   ├── Transaction/
│   │   └── Report/
│   └── Unit/
│       ├── Services/
│       ├── Repositories/
│       └── Models/
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md
```

---

## Key Features & Enhancements

### 1. **Authentication & Authorization**
- Role-based access control (RBAC)
- Permission management
- Middleware untuk check role dan permission
- Audit logging untuk setiap aksi user

### 2. **Master Data Management**
- **Items/Barang**
  - Kode barang (auto-generate atau manual)
  - Nama, deskripsi
  - Kategori, unit
  - Foto barang (multiple images)
  - Barcode/QR code
  - Min stock, max stock
  - Harga beli, harga jual
  - Status (active/inactive)
  
- **Kategori**
  - Hierarchical categories (parent-child)
  - Icon/image untuk kategori
  
- **Unit/Satuan**
  - Base unit dan conversion
  - Contoh: 1 Box = 12 Pcs
  
- **Lokasi/Location**
  - Warehouse/gudang
  - Rack/rak
  - Bin/tempat
  - Hierarchical structure
  
- **Supplier**
  - Data supplier lengkap
  - Contact person
  - Payment terms
  
- **Customer** (jika ada distribusi keluar)
  - Data pelanggan
  - Credit limit
  - Payment terms

### 3. **Inventory Management**
- **Stock Management**
  - Real-time stock tracking
  - Multi-location inventory
  - Batch/lot tracking
  - Serial number tracking
  - Expiry date tracking
  
- **Stock Movement**
  - Purchase/Pembelian
  - Sales/Penjualan
  - Transfer antar lokasi
  - Adjustment
  - Return
  - Opname/stock take
  - Full audit trail
  
- **Stock Opname**
  - Scheduled atau ad-hoc
  - Physical count vs system
  - Adjustment generation
  - Approval workflow

### 4. **Transaction Management**
- **Purchase Order (PO)**
  - Create, edit, approve PO
  - PO tracking
  - Partial receipt
  
- **Goods Receipt/Penerimaan Barang**
  - Receipt dari PO
  - Direct receipt (tanpa PO)
  - Quality check notes
  - Batch/lot assignment
  
- **Goods Issue/Pengeluaran Barang**
  - Issue untuk produksi
  - Issue untuk sales
  - Issue untuk internal use
  - Return handling

### 5. **Reporting & Analytics**
- **Stock Reports**
  - Current stock by location
  - Stock by category
  - Low stock alert
  - Overstock report
  
- **Movement Reports**
  - Stock in/out summary
  - Movement by period
  - Movement by user
  
- **Valuation Reports**
  - Inventory value (FIFO/LIFO/Average)
  - Cost analysis
  
- **Aging Reports**
  - Slow moving items
  - Fast moving items
  - Dead stock
  
- **Export Options**
  - PDF
  - Excel
  - CSV

### 6. **Notifications & Alerts**
- Low stock notifications
- Expiry date warnings
- Stock movement alerts
- Approval notifications
- Email dan in-app notifications

### 7. **Advanced Features**
- **Barcode/QR Code**
  - Generate barcode untuk items
  - Mobile scanning support
  
- **Import/Export**
  - Bulk import items
  - Bulk import stock
  - Template download
  
- **Dashboard**
  - Real-time metrics
  - Charts dan graphs
  - Recent activities
  - Quick actions
  
- **API**
  - RESTful API
  - API versioning
  - Authentication via token
  - Rate limiting
  
- **Multi-tenancy Support** (optional)
  - Multiple companies/branches
  - Isolated data
  
- **Approval Workflow**
  - Configurable approval levels
  - Notification system
  - History tracking

### 8. **Integration Ready**
- Queue system untuk heavy operations
- Event-driven architecture
- Webhook support
- Third-party API integration ready

---

## Technical Stack

### Backend
- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL
- **Cache**: Redis
- **Queue**: Redis/Database
- **Storage**: Local/S3

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS / Bootstrap 5
- **JS Framework**: Alpine.js / Vue.js
- **Build Tool**: Vite
- **DataTables**: jQuery DataTables / Livewire Tables
- **Charts**: Chart.js / ApexCharts
- **Icons**: Font Awesome / Heroicons

### Additional Libraries
- **Barcode**: `milon/barcode` atau `picqer/php-barcode-generator`
- **QR Code**: `simplesoftwareio/simple-qrcode`
- **Export**: `maatwebsite/excel`
- **PDF**: `barryvdh/laravel-dompdf` atau `spatie/laravel-pdf`
- **Image**: `intervention/image`
- **Audit**: `owen-it/laravel-auditing`
- **Permission**: `spatie/laravel-permission`
- **Activity Log**: `spatie/laravel-activitylog`

---

## Database Structure

**[PENDING]** - Database structure akan diisi setelah user memberikan ERD/struktur database.

### Planned Tables:
```
# User Management
- users
- roles
- permissions
- role_user
- permission_role

# Master Data
- categories
- units
- unit_conversions
- locations
- warehouses
- suppliers
- customers

# Inventory
- items
- item_images
- stocks
- stock_movements
- stock_opnames
- stock_opname_details
- batches
- serial_numbers

# Transactions
- purchase_orders
- purchase_order_details
- receipts
- receipt_details
- issues
- issue_details
- transfers
- transfer_details

# System
- audit_logs
- activity_logs
- notifications
- settings
```

---

## API Endpoints Structure

### Authentication
```
POST   /api/v1/auth/register
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
POST   /api/v1/auth/refresh
GET    /api/v1/auth/me
```

### Items
```
GET    /api/v1/items
POST   /api/v1/items
GET    /api/v1/items/{id}
PUT    /api/v1/items/{id}
DELETE /api/v1/items/{id}
GET    /api/v1/items/{id}/stock
POST   /api/v1/items/bulk-import
```

### Stock
```
GET    /api/v1/stocks
GET    /api/v1/stocks/{id}
POST   /api/v1/stocks/movement
GET    /api/v1/stocks/movements
POST   /api/v1/stocks/adjustment
POST   /api/v1/stocks/transfer
```

### Reports
```
GET    /api/v1/reports/stock
GET    /api/v1/reports/movement
GET    /api/v1/reports/valuation
GET    /api/v1/reports/aging
POST   /api/v1/reports/export
```

---

## Middleware Stack

1. **Authentication**: `auth`, `auth:sanctum`
2. **Role Check**: `role:admin,manager`
3. **Permission Check**: `permission:view-items,create-items`
4. **Audit Log**: `audit.log`
5. **API Rate Limiting**: `throttle:api`
6. **CORS**: Configured in `cors.php`

---

## Design Patterns & Best Practices

### Architecture Patterns
- **Repository Pattern**: Abstraksi database operations
- **Service Layer**: Business logic separation
- **Observer Pattern**: Model events handling
- **Factory Pattern**: Object creation
- **Strategy Pattern**: Payment/calculation methods

### Code Quality
- PSR-12 coding standard
- Type hinting
- DocBlocks untuk semua methods
- Unit tests coverage > 80%
- Feature tests untuk critical paths

### Security
- CSRF protection
- XSS prevention
- SQL injection prevention via Eloquent
- Input validation pada semua forms
- Rate limiting pada API
- Secure file upload handling
- Encrypted sensitive data

### Performance
- Query optimization (N+1 prevention)
- Eager loading
- Database indexing
- Redis caching
- Queue untuk heavy jobs
- Asset optimization (Vite)
- Lazy loading images

---

## Deployment Checklist

### Server Requirements
- PHP 8.2+
- Composer 2.x
- Node.js 18+
- MySQL 8.0+ / PostgreSQL
- Redis
- Supervisor (untuk queue workers)

### Environment Variables
```
APP_NAME="Inventaris Barang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://inventory.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

FILESYSTEM_DISK=local
```

### Deployment Steps
1. Clone repository
2. `composer install --optimize-autoloader --no-dev`
3. `npm install && npm run build`
4. Copy `.env.example` ke `.env`
5. `php artisan key:generate`
6. `php artisan migrate --seed`
7. `php artisan storage:link`
8. `php artisan config:cache`
9. `php artisan route:cache`
10. `php artisan view:cache`
11. Setup supervisor untuk queue workers
12. Setup cron untuk scheduler

---

## Development Workflow

### Local Setup
```bash
# Clone repository
git clone [repository-url]
cd inventaris-barang

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate:fresh --seed

# Storage link
php artisan storage:link

# Development server
php artisan serve
npm run dev

# Queue worker (separate terminal)
php artisan queue:work
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ItemTest

# Generate coverage
php artisan test --coverage
```

---

## Roadmap & Future Enhancements

### Phase 1 (MVP)
- ✅ User authentication & authorization
- ✅ Master data management (items, categories, units)
- ✅ Basic stock management
- ✅ Stock movement tracking
- ✅ Basic reporting

### Phase 2
- Multi-location inventory
- Purchase order management
- Receipt & issue transactions
- Advanced reporting
- Export functionality

### Phase 3
- Barcode/QR scanning
- Mobile app integration
- Approval workflows
- Email notifications
- Batch & serial tracking

### Phase 4
- Multi-tenancy
- Advanced analytics
- Predictive analytics (AI/ML)
- Mobile app (React Native/Flutter)
- Integration dengan accounting system

---

## Notes

1. **Clean Code**: Ikuti SOLID principles
2. **Documentation**: Setiap fitur harus didokumentasikan
3. **Testing**: Write tests sebelum atau bersamaan dengan development
4. **Git Workflow**: Feature branch → PR → Review → Merge
5. **Code Review**: Mandatory sebelum merge ke main branch
6. **Version Control**: Semantic versioning (MAJOR.MINOR.PATCH)

---

## Contact & Support

- **Developer**: [Your Name]
- **Project Manager**: [PM Name]
- **Repository**: [Git URL]
- **Documentation**: [Docs URL]

---

**Last Updated**: 2025-11-26  
**Version**: 1.0.0  
**Status**: Planning Phase - Awaiting Database Structure
