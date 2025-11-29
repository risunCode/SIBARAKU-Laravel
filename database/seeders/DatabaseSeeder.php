<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Only creates minimal admin user for fresh installation.
     * 
     * For demo data, run: php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"
     */
    public function run(): void
    {
        $this->command->info('🔧 Running minimal SIBARANG installation...');
        
        $this->call([
            UserSeeder::class,        // Create admin user only
        ]);
        
        $this->command->info('✅ Minimal installation completed!');
        $this->command->info('');
        $this->command->info('📝 To add demo data, run:');
        $this->command->info('   php artisan db:seed --class="Database\\Demo\\DemoSeeder"');
    }
}
