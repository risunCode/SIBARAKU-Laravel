<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates essential locations for production use.
     */
    public function run(): void
    {
        $this->command->info('📍 Creating essential locations...');
        
        $locations = [
            [
                'name' => 'Gedung Utama',
                'code' => 'GU',
                'description' => 'Gedung utama kantor pusat',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 1',
                'room' => 'Ruang Administrasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gedung Belakang',
                'code' => 'GB',
                'description' => 'Gedung belakang untuk gudang',
                'building' => 'Gedung Belakang',
                'floor' => 'Lantai 1',
                'room' => 'Gudang Utama',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Server',
                'code' => 'RS',
                'description' => 'Ruang server utama',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'Ruang Server',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Direksi',
                'code' => 'RD',
                'description' => 'Ruang direksi dan manajemen',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 3',
                'room' => 'Ruang Direksi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Meeting',
                'code' => 'RM',
                'description' => 'Ruang meeting utama',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'Ruang Meeting',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('locations')->insert($locations);
        
        $this->command->info('✅ Created ' . count($locations) . ' essential locations');
    }
}
