<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\Category;
use App\Models\Location;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Seed demo data for testing purposes.
     */
    public function run(): void
    {
        $this->command->info('🎭 Running SIBARAKU demo data installation...');

        // Create referral codes
        $this->command->info('🎟️ Creating referral codes...');
        $admin = User::where('role', 'admin')->first();
        
        if ($admin) {
            ReferralCode::firstOrCreate(
                ['code' => 'ADMIN2025'],
                ['created_by' => $admin->id, 'role' => 'admin', 'max_uses' => 3, 'is_active' => true]
            );

            ReferralCode::firstOrCreate(
                ['code' => 'STAFF2025'],
                ['created_by' => $admin->id, 'role' => 'staff', 'max_uses' => 10, 'is_active' => true]
            );
        }

        // Create demo commodities
        $this->command->info('📦 Creating demo commodities...');
        
        $category = Category::first();
        $location = Location::first();
        $user = $admin;

        if ($category && $location && $user) {
            $demoItems = [
                ['name' => 'Laptop ASUS VivoBook', 'brand' => 'ASUS', 'condition' => 'baik', 'price' => 8500000],
                ['name' => 'Printer HP LaserJet', 'brand' => 'HP', 'condition' => 'baik', 'price' => 3500000],
                ['name' => 'Monitor LG 24 inch', 'brand' => 'LG', 'condition' => 'baik', 'price' => 2500000],
                ['name' => 'Keyboard Logitech', 'brand' => 'Logitech', 'condition' => 'baik', 'price' => 350000],
                ['name' => 'Mouse Wireless', 'brand' => 'Logitech', 'condition' => 'baik', 'price' => 250000],
            ];

            $year = date('Y');
            $count = 1;

            foreach ($demoItems as $item) {
                Commodity::create([
                    'item_code' => sprintf('%s-%s-%04d', $category->code ?? 'INV', $year, $count),
                    'name' => $item['name'],
                    'brand' => $item['brand'],
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'condition' => $item['condition'],
                    'purchase_price' => $item['price'],
                    'purchase_year' => $year,
                    'created_by' => $user->id,
                ]);
                $count++;
            }
        }

        $this->command->info('');
        $this->command->info('✅ Demo data installation completed!');
        $this->command->info('');
        $this->command->info('📊 Created:');
        $this->command->info('   - 2 Referral codes (ADMIN2025, STAFF2025)');
        $this->command->info('   - 5 Demo commodities');
    }
}
