<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Peralatan Kantor',
                'code' => 'PKT',
                'description' => 'Peralatan dan perlengkapan kantor',
                'children' => [
                    ['name' => 'Komputer & Laptop', 'code' => 'KOM', 'description' => 'Komputer desktop, laptop, dan tablet'],
                    ['name' => 'Printer & Scanner', 'code' => 'PRT', 'description' => 'Printer, scanner, dan mesin fotokopi'],
                    ['name' => 'Telepon & Fax', 'code' => 'TEL', 'description' => 'Telepon, fax, dan alat komunikasi'],
                    ['name' => 'Peralatan Presentasi', 'code' => 'PRE', 'description' => 'Proyektor, layar, dan whiteboard'],
                ],
            ],
            [
                'name' => 'Mebel & Furnitur',
                'code' => 'MBL',
                'description' => 'Mebel dan furnitur kantor',
                'children' => [
                    ['name' => 'Meja', 'code' => 'MJA', 'description' => 'Meja kerja, meja rapat, dan meja resepsionis'],
                    ['name' => 'Kursi', 'code' => 'KRS', 'description' => 'Kursi kerja, kursi tamu, dan sofa'],
                    ['name' => 'Lemari & Rak', 'code' => 'LMR', 'description' => 'Lemari arsip, rak buku, dan filling cabinet'],
                    ['name' => 'Partisi', 'code' => 'PTS', 'description' => 'Partisi ruangan dan cubicle'],
                ],
            ],
            [
                'name' => 'Kendaraan',
                'code' => 'KDR',
                'description' => 'Kendaraan dinas',
                'children' => [
                    ['name' => 'Mobil', 'code' => 'MOB', 'description' => 'Mobil dinas'],
                    ['name' => 'Motor', 'code' => 'MTR', 'description' => 'Motor dinas'],
                ],
            ],
            [
                'name' => 'Elektronik',
                'code' => 'ELK',
                'description' => 'Peralatan elektronik',
                'children' => [
                    ['name' => 'AC & Pendingin', 'code' => 'ACC', 'description' => 'AC, kipas angin, dan pendingin'],
                    ['name' => 'TV & Audio', 'code' => 'TVA', 'description' => 'TV, speaker, dan sound system'],
                    ['name' => 'CCTV & Keamanan', 'code' => 'CTV', 'description' => 'CCTV, alarm, dan peralatan keamanan'],
                ],
            ],
            [
                'name' => 'Alat Tulis Kantor',
                'code' => 'ATK',
                'description' => 'Alat tulis dan perlengkapan kantor habis pakai',
                'children' => [],
            ],
            [
                'name' => 'Lainnya',
                'code' => 'LNY',
                'description' => 'Kategori lainnya',
                'children' => [],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $parent->id;
                Category::create($childData);
            }
        }
    }
}
