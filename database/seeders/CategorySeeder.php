<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates essential categories for production use.
     */
    public function run(): void
    {
        $this->command->info('📁 Creating essential categories...');
        
        $categories = [
            // Parent Categories dengan nama yang DISTINCT
            [
                'name' => 'KATEGORI INDUK - IT',
                'code' => 'TIK',
                'description' => 'Induk kategori untuk Peralatan IT',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KATEGORI INDUK - ELEKTRONIK',
                'code' => 'ELK',
                'description' => 'Induk kategori untuk Elektronik Kantor',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KATEGORI INDUK - ATK',
                'code' => 'ATK',
                'description' => 'Induk kategori untuk Alat Tulis Kantor',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KATEGORI INDUK - KENDARAAN',
                'code' => 'KMP',
                'description' => 'Induk kategori untuk Kendaraan Operasional',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KATEGORI INDUK - RUMAH TANGGA',
                'code' => 'PRT',
                'description' => 'Induk kategori untuk Peralatan Rumah Tangga',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Example Child Categories dengan nama yang BERBEDA dari induk
            [
                'name' => 'Laptop & Komputer',
                'code' => 'TIK-LAP',
                'description' => 'Laptop dan komputer personal',
                'parent_id' => 1, // KATEGORI INDUK - IT
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Printer & Scanner',
                'code' => 'TIK-PRT',
                'description' => 'Printer dan scanner kantor',
                'parent_id' => 1, // KATEGORI INDUK - IT
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monitor & Display',
                'code' => 'ELK-MON',
                'description' => 'Monitor dan display unit',
                'parent_id' => 2, // KATEGORI INDUK - ELEKTRONIK
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pulpen & Pensil',
                'code' => 'ATK-PEN',
                'description' => 'Alat tulis coretan',
                'parent_id' => 3, // KATEGORI INDUK - ATK
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobil Dinas',
                'code' => 'KMP-CAR',
                'description' => 'Mobil dinas operasional',
                'parent_id' => 4, // KATEGORI INDUK - KENDARAAN
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
        
        $this->command->info('✅ Created ' . count($categories) . ' essential categories');
    }
}
