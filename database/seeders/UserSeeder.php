<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (hanya 1)
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@inventaris.local',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'birth_date' => '1990-01-01',
            'is_active' => true,
            'email_verified_at' => now(),
            'referral_code' => 'INV-SUPER-ADMIN01',
            'security_question_1' => 1,
            'security_answer_1' => Hash::make('jawaban1'),
            'security_question_2' => 2,
            'security_answer_2' => Hash::make('jawaban2'),
            'security_setup_completed' => true,
        ]);
        $superAdmin->assignRole('super-admin');
    }
}
