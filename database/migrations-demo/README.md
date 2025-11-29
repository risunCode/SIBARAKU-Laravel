# MIGRATIONS-DEMO

Folder ini berisi semua data demo untuk SIBARANG.

## Cara Pakai:

```bash
# Untuk menjalankan demo data setelah production install
php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"

# Atau langsung dari folder ini
php artisan db:seed --class="Database\MigrationsDemo\DemoSeeder"
```

## Isi Folder:

- **CategorySeeder.php** - 6 kategori tambahan (PKL, MFN, KR2, PML, TKL, LNY)
- **LocationSeeder.php** - 10 lokasi tambahan (RKD, RSD, RRK, RSA, RSB, RIT, GDK, ARE, RAD, ALB)  
- **CommoditySeeder.php** - 18 sample commodities
- **DemoSeeder.php** - Main orchestrator

## Production Data:

Production data tetap di `database/seeders/`:
- 5 essential categories (ATK, ELK, KMP, TIK, KBM)
- 5 essential locations (GU, GB, RS, RD, RM)
- 3 referral codes
- 1 admin user
