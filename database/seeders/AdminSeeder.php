<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ReferralCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('admin123456'),
            'phone' => '08123456789',
            'role' => 'admin',
            'is_active' => true,
            'birth_date' => '1990-01-01',
            'security_question_1' => '1', // Question: Siapa nama ibu kandung Anda?
            'security_answer_1' => Hash::make('admin'), // Answer: admin
            'security_setup_completed' => true,
            'email_verified_at' => now(),
        ]);

        echo "✅ Admin created: {$admin->email} (Password: admin123456)\n";

        // Create Admin Referral Code
        $adminReferralCode = ReferralCode::create([
            'code' => 'ADMIN2024',
            'description' => 'Kode referral untuk Admin',
            'max_uses' => 5,
            'role' => 'admin',
            'expires_at' => now()->addYear(),
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Create Staff Referral Code
        $staffReferralCode = ReferralCode::create([
            'code' => 'STAFF2024',
            'description' => 'Kode referral untuk Staff',
            'max_uses' => 50,
            'role' => 'staff',
            'expires_at' => now()->addYear(),
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Create User Referral Code
        $userReferralCode = ReferralCode::create([
            'code' => 'USER2024',
            'description' => 'Kode referral untuk User biasa',
            'max_uses' => 0, // Unlimited
            'role' => 'user',
            'expires_at' => now()->addYear(),
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        echo "✅ Referral codes created:\n";
        echo "   - ADMIN2024 (Role: admin, Max: 5)\n";
        echo "   - STAFF2024 (Role: staff, Max: 50)\n";
        echo "   - USER2024 (Role: user, Unlimited)\n";

        echo "\n🎯 Login Information:\n";
        echo "   Email: admin@inventaris.com\n";
        echo "   Password: admin123456\n";
        echo "   Security Answer: admin\n";
    }
}
