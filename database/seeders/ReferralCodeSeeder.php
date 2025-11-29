<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReferralCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates essential referral codes for production use.
     */
    public function run(): void
    {
        $this->command->info('🎫 Creating essential referral codes...');
        
        // Get admin user for created_by reference
        $adminUser = User::where('email', 'admin@inventaris.com')->first();
        
        if (!$adminUser) {
            $this->command->error('❌ Admin user not found! Please run UserSeeder first.');
            return;
        }

        $referralCodes = [
            [
                'code' => 'ADMIN2025',
                'description' => 'Kode referral administrator',
                'created_by' => $adminUser->id,
                'max_uses' => null, // unlimited
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'STAFF2025',
                'description' => 'Kode referral staff',
                'created_by' => $adminUser->id,
                'max_uses' => 100,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DEMO2025',
                'description' => 'Kode referral untuk demo',
                'created_by' => $adminUser->id,
                'max_uses' => 50,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('referral_codes')->insert($referralCodes);
        
        $this->command->info('✅ Created ' . count($referralCodes) . ' essential referral codes');
    }
}
