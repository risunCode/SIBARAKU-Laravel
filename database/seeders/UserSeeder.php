<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates minimal admin user for fresh installation.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Administrator SIBARAKU',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('panelsibaraku'),
            'phone' => '+6281234567890',
            'birth_date' => '1990-01-01',
            'is_active' => true,
            'security_setup_completed' => true,
            'role' => 'admin',
        ]);

        $this->command->info('✅ Admin user created successfully');
        $this->command->info("   Email: admin@inventaris.com");
        $this->command->info("   Password: panelsibaraku");
        $this->command->info("   Security Setup: Completed (default)");
    }
}
