#!/bin/bash

# SIBARANG Production Optimization Script
# Run this after deployment to production

echo "🚀 Optimizing SIBARANG for Production..."

# Clear all caches first
echo "📋 Clearing existing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
echo "📦 Optimizing Composer autoloader..."
composer install --optimize-autoloader --no-dev

# Build production assets (if not done)
echo "🎨 Building production assets..."
npm run build

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Clear logs (keep only last 7 days)
echo "🧹 Cleaning old logs..."
find storage/logs -name "*.log" -mtime +7 -delete 2>/dev/null || true

echo "✅ Production optimization complete!"
echo ""
echo "📊 Cache Status:"
php artisan about --only=cache

echo ""
echo "🎯 Next Steps:"
echo "1. Set APP_ENV=production in .env"
echo "2. Set APP_DEBUG=false in .env" 
echo "3. Configure proper database credentials"
echo "4. Set up SSL certificate"
echo "5. Configure web server (Apache/Nginx)"
