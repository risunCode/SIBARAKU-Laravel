<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo database seeds.
     * This contains all sample data for demonstration purposes.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting SIBARANG Demo Data Seeding...');
        
        $this->command->info('📁 Creating demo categories...');
        $this->call([
            CategorySeeder::class,
        ]);

        $this->command->info('🏢 Creating demo locations...');
        $this->call([
            LocationSeeder::class,
        ]);

        $this->command->info('📦 Creating demo commodities...');
        $this->call([
            CommoditySeeder::class,
        ]);

        $this->command->info('✅ Demo data seeding completed!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Categories: 19 (10 main + 9 subcategories)');
        $this->command->info('   - Locations: 10 (realistic office setup)');
        $this->command->info('   - Commodities: 18 (diverse sample items)');
        $this->command->info('');
        $this->command->info('🔑 Admin credentials:');
        $this->command->info('   Email: admin@inventaris.com');
        $this->command->info('   Password: panelsibarang');
        $this->command->info('');
        $this->command->info('🎯 Ready for testing and demonstration!');
    }
}
