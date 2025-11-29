<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Creates essential production data: admin, categories, locations, referral codes.
     * 
     * For complete demo data, run: php artisan db:seed --class="Database\Seeders\Demo\DemoSeeder"
     */
    public function run(): void
    {
        $this->command->info('🔧 Running SIBARANG production installation...');
        
        $this->call([
            UserSeeder::class,        // Create admin user
            CategorySeeder::class,    // Create essential categories
            LocationSeeder::class,    // Create essential locations  
            ReferralCodeSeeder::class, // Create essential referral codes
        ]);
        
        $this->command->info('✅ Production installation completed!');
        $this->command->info('');
        $this->command->info('📊 Created:');
        $this->command->info('   - 1 Admin user');
        $this->command->info('   - 5 Essential categories');
        $this->command->info('   - 5 Essential locations');
        $this->command->info('   - 3 Referral codes');
        $this->command->info('');
        $this->command->info('📝 To add complete demo data, run:');
        $this->command->info('   php artisan db:seed --class="Database\\MigrationsDemo\\DemoSeeder"');
    }
}
