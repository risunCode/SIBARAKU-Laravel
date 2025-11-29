<?php

namespace Database\MigrationsDemo;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing production categories as parents
        $parentCategories = [
            'ATK' => [
                'children' => [
                    ['name' => 'Kertas A4 & F4', 'code' => 'KTR', 'description' => 'Kertas ukuran A4, F4, dan lainnya'],
                    ['name' => 'Pulpen & Pensil', 'code' => 'PPN', 'description' => 'Pulpen, pensil, ballpoint, dan alat tulis'],
                    ['name' => 'Map & Folder', 'code' => 'MAP', 'description' => 'Map, folder, clear holder, dan map plastik'],
                    ['name' => 'Stapler & Cutter', 'code' => 'STP', 'description' => 'Stapler, cutter, gunting, dan alat kantor'],
                ],
            ],
            'ELK' => [
                'children' => [
                    ['name' => 'PC All-in-One', 'code' => 'PCA', 'description' => 'PC AIO Lenovo, HP, Dell, dan lainnya'],
                    ['name' => 'Printer & Scanner', 'code' => 'PRS', 'description' => 'Printer, scanner, dan mesin fotokopi'],
                    ['name' => 'Monitor LCD', 'code' => 'MON', 'description' => 'Monitor LCD, LED, dan layar komputer'],
                    ['name' => 'Proyektor', 'code' => 'PRJ', 'description' => 'Proyektor, layar presentasi, dan perlengkapannya'],
                ],
            ],
            'TIK' => [
                'children' => [
                    ['name' => 'Laptop & Notebook', 'code' => 'LTP', 'description' => 'Laptop, notebook, dan ultrabook'],
                    ['name' => 'Komputer Desktop', 'code' => 'DTP', 'description' => 'PC desktop, CPU, dan komponen'],
                    ['name' => 'Keyboard & Mouse', 'code' => 'KBM', 'description' => 'Keyboard, mouse, dan input devices'],
                    ['name' => 'Hardisk & Flashdisk', 'code' => 'HDD', 'description' => 'Hardisk eksternal, flashdisk, dan storage'],
                ],
            ],
            'KMP' => [
                'children' => [
                    ['name' => 'Mobil Dinas', 'code' => 'MOB', 'description' => 'Mobil dinas operasional'],
                    ['name' => 'Motor Dinas', 'code' => 'MTR', 'description' => 'Motor dinas operasional'],
                ],
            ],
            'PRT' => [
                'children' => [
                    ['name' => 'Galon Air Minum', 'code' => 'GLN', 'description' => 'Galon air mineral dan dispenser'],
                    ['name' => 'Peralatan Kebersihan', 'code' => 'KBS', 'description' => 'Sapu, pel, kain lap, dan alat kebersihan'],
                    ['name' => 'Dispenser & Kulkas', 'code' => 'DSP', 'description' => 'Dispenser, kulkas mini, dan pendingin'],
                ],
            ],
        ];

        foreach ($parentCategories as $parentCode => $categoryData) {
            // Find existing parent category
            $parent = Category::where('code', $parentCode)->first();
            
            if (!$parent) {
                $this->command->warn("Parent category {$parentCode} not found, skipping children");
                continue;
            }

            $children = $categoryData['children'] ?? [];

            foreach ($children as $childData) {
                // Check if child category already exists
                $existingChild = Category::where('code', $childData['code'])->first();
                
                if (!$existingChild) {
                    $childData['parent_id'] = $parent->id;
                    $childData['is_active'] = true;
                    $childData['created_at'] = now();
                    $childData['updated_at'] = now();
                    
                    Category::create($childData);
                }
            }
        }
    }
}
