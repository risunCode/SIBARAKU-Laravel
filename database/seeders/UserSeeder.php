<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with admin user.
     */
    public function run(): void
    {
        $this->command->info('👤 Creating admin user...');

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('panelsibaraku'),
            'role' => 'admin',
            'is_active' => true,
            'security_setup_completed' => false,
        ]);

        $this->command->info('✅ Admin user created');
        $this->command->info('   Email: admin@inventaris.com');
        $this->command->info('   Password: panelsibaraku');
    }
}
