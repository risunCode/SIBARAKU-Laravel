---
description: Repository Information Overview
alwaysApply: true
---

# SIBARANG - Sistem Inventaris Barang

## Summary
SIBARANG (Sistem Inventaris Barang) v1.0.0 is an inventory management system designed for government institutions, BUMN/BUMD, and private companies. Built with Laravel 12, it provides comprehensive functionality for managing commodities, locations, transfers, maintenance, and disposals with a modern PHP/JavaScript stack.

## Structure
**Main Directories**:
- **app/** - Application core (Controllers, Models, Enums, Helpers, Notifications, Observers, Providers)
- **resources/** - Frontend assets (CSS with TailwindCSS, JS, Blade views)
- **routes/** - Web and API route definitions
- **database/** - Migrations, seeders, and factories
- **tests/** - Unit and Feature tests
- **config/** - Application configuration files
- **public/** - Public entry point and static assets
- **storage/** - File storage and logs
- **bootstrap/** - Framework bootstrap files

**Key Components**:
- Inventory management (commodities, categories)
- Location tracking
- Transfer management
- Maintenance scheduling
- Disposal handling
- Activity logging
- User management with referral codes
- Notifications system

## Language & Runtime
**Language**: PHP  
**Version**: ^8.2 (Tested on 8.3.23)  
**Framework**: Laravel 12.0  
**Build System**: Composer 2.x + Vite 7  
**Package Manager**: Composer (PHP), npm (JavaScript)  
**Node.js**: 18+ recommended  
**Database**: MySQL 8.0 / MariaDB 10.6+ / SQLite 3.x

## Dependencies

**Main PHP Dependencies** (composer.json):
- laravel/framework: ^12.0 - Core framework
- barryvdh/laravel-dompdf: ^3.1 - PDF generation for reports
- intervention/image-laravel: ^1.5 - Image processing for commodity photos
- maatwebsite/excel: ^1.1 - Excel import/export functionality
- laravel/tinker: ^2.10.1 - REPL for debugging

**Development PHP Dependencies**:
- fakerphp/faker: ^1.23 - Fake data generation
- laravel/pail: ^1.2.2 - Log viewer
- laravel/pint (code style)
- laravel/sail (Docker environment)
- mockery/mockery (testing)
- nunomaduro/collision (error handling)
- phpunit/phpunit (testing framework)

**JavaScript Dependencies** (package.json):
- vite: ^7.0.7 - Frontend build tool
- tailwindcss: ^4.0.0 - CSS framework
- @tailwindcss/vite: ^4.0.0 - TailwindCSS Vite integration
- laravel-vite-plugin: ^2.0.0 - Laravel-Vite bridge
- axios: ^1.11.0 - HTTP client
- @heroicons/react: ^2.2.0 - Icon library
- concurrently: ^9.0.1 - Run multiple commands

## Build & Installation

**Initial Setup**:
```bash
# Install dependencies
composer install
npm install

# Environment configuration
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed
php artisan storage:link

# Build assets
npm run build
```

**Development Mode**:
```bash
# Terminal 1: Run Laravel development server
php artisan serve

# Terminal 2: Run Vite for HMR
npm run dev
```

**Production Build**:
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Optimization Scripts**:
- **Windows**: `optimize-production.bat`
- **Linux/Mac**: `optimize-production.sh`

## Main Files

**Entry Points**:
- **public/index.php** - Main application entry point
- **artisan** - CLI command interface
- **resources/js/app.js** - JavaScript entry point
- **resources/css/app.css** - CSS entry point

**Configuration**:
- **.env** - Environment variables (copy from .env.example)
- **config/sibarang.php** - Application-specific settings
- **config/security_questions.php** - Security question configuration
- **vite.config.js** - Frontend build configuration

**Routes**:
- **routes/web.php** - Web application routes
- **routes/api.php** - API endpoints
- **routes/console.php** - Artisan commands

**Core Models** (app/Models/):
- User, Category, Commodity, CommodityImage
- Location, Transfer, Maintenance, Disposal
- ActivityLog, ReferralCode

**Controllers** (app/Http/Controllers/):
- DashboardController, CategoryController, CommodityController
- LocationController, TransferController, MaintenanceController
- DisposalController, UserController, NotificationController
- ReportController, ReferralCodeController

## Testing

**Framework**: PHPUnit  
**Test Location**: `tests/` directory  
**Naming Convention**: 
- Unit tests: `tests/Unit/*Test.php`
- Feature tests: `tests/Feature/*Test.php`

**Configuration**: `phpunit.xml`  
**Test Suites**: Unit and Feature

**Run Tests**:
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/ExampleTest.php
```

**Test Environment**:
- Uses in-memory SQLite database
- Environment variables configured in phpunit.xml
- Bootstrap file: `tests/TestCase.php`

## Development

**Hot Module Reload**: Vite dev server on http://127.0.0.1:5173 with HMR enabled

**Code Standards**: Laravel PSR-2 compliant (enforced via Pint)

**Database Seeding**:
```bash
# Fresh migration with seeders
php artisan migrate:fresh --seed

# Specific seeder
php artisan db:seed --class=UserSeeder
```

**Cache Management**:
```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Logging**: Configured in `config/logging.php`, logs stored in `storage/logs/`

**Additional Documentation**:
- **DEVELOPMENT.md** - Comprehensive development guide
- **ROUTING.md** - Route documentation
- **CHANGELOG.md** - Version history
