@echo off
REM SIBARANG Production Optimization Script (Windows)
REM Run this after deployment to production

echo 🚀 Optimizing SIBARANG for Production...

REM Clear all caches first
echo 📋 Clearing existing caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

REM Optimize for production
echo ⚡ Caching configurations...
php artisan config:cache
php artisan route:cache
php artisan view:cache

REM Optimize Composer autoloader
echo 📦 Optimizing Composer autoloader...
composer install --optimize-autoloader --no-dev

REM Build production assets (if not done)
echo 🎨 Building production assets...
npm run build

echo ✅ Production optimization complete!
echo.
echo 📊 Cache Status:
php artisan about --only=cache

echo.
echo 🎯 Next Steps:
echo 1. Set APP_ENV=production in .env
echo 2. Set APP_DEBUG=false in .env
echo 3. Configure proper database credentials
echo 4. Set up SSL certificate
echo 5. Configure web server (IIS/Apache)

pause
