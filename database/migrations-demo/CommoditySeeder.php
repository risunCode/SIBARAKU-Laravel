<?php

namespace Database\MigrationsDemo;

use Illuminate\Database\Seeder;
use App\Models\Commodity;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;

class CommoditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user for created_by
        $adminUser = User::where('email', 'admin@inventaris.com')->first();
        
        // Get categories and locations
        $categories = Category::all()->keyBy('code');
        $locations = Location::all()->keyBy('code');

        $commodities = [
            // Laptop (Peralatan IT)
            [
                'name' => 'Laptop Dell Inspiron 15',
                'category_code' => 'LTP',
                'location_code' => 'GU',
                'brand' => 'Dell',
                'model' => 'Inspiron 15 3511',
                'serial_number' => 'DL2024001',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Teknologi Indonesia',
                'quantity' => 5,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 8500000,
                'specifications' => 'Intel Core i5-1135G7, 8GB RAM, 512GB SSD, 15.6" FHD',
                'responsible_person' => 'Ahmad Wijaya',
            ],
            [
                'name' => 'Laptop HP ProBook 440',
                'category_code' => 'LTP',
                'location_code' => 'RD',
                'brand' => 'HP',
                'model' => 'ProBook 440 G9',
                'serial_number' => 'HP2024002',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Solusi Komputer',
                'quantity' => 3,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 9200000,
                'specifications' => 'Intel Core i7-1255U, 16GB RAM, 1TB SSD, 14" FHD',
                'responsible_person' => 'Siti Nurhaliza',
            ],
            
            // Printer (Elektronik Kantor)
            [
                'name' => 'Printer Canon PIXMA G3010',
                'category_code' => 'PRS',
                'location_code' => 'RLJ',
                'brand' => 'Canon',
                'model' => 'PIXMA G3010',
                'serial_number' => 'CN2024003',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Alat Tulis Sukses',
                'quantity' => 2,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 1850000,
                'specifications' => 'Ink Tank, WiFi, Print/Scan/Copy, 4800x1200 dpi',
                'responsible_person' => 'Budi Santoso',
            ],
            [
                'name' => 'Printer Epson L3150',
                'category_code' => 'PRS',
                'location_code' => 'GPB',
                'brand' => 'Epson',
                'model' => 'EcoTank L3150',
                'serial_number' => 'EP2024004',
                'acquisition_type' => 'hibah',
                'acquisition_source' => 'Donor PT. Print Mandiri',
                'quantity' => 1,
                'condition' => 'rusak_ringan',
                'purchase_year' => 2023,
                'purchase_price' => 0,
                'specifications' => 'Ink Tank, WiFi, Print/Scan/Copy, 5760x1440 dpi',
                'responsible_person' => 'Ahmad Wijaya',
            ],

            // PC All-in-One (Elektronik Kantor)
            [
                'name' => 'PC All-in-One Lenovo ThinkCentre',
                'category_code' => 'PCA',
                'location_code' => 'RLJ',
                'brand' => 'Lenovo',
                'model' => 'ThinkCentre M70q',
                'serial_number' => 'LN2024005',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Komputer Maju',
                'quantity' => 5,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 7500000,
                'specifications' => 'Intel Core i5, 8GB RAM, 256GB SSD, 21.5" FHD Touch',
                'responsible_person' => 'Bagian Umum',
            ],
            // Monitor LCD (Elektronik Kantor)
            [
                'name' => 'Monitor LG 24" LED',
                'category_code' => 'MON',
                'location_code' => 'RRP',
                'brand' => 'LG',
                'model' => '24MN430H-B',
                'serial_number' => 'LG2024006',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Monitor Indonesia',
                'quantity' => 8,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 1500000,
                'specifications' => '24" Full HD LED, HDMI, VGA, 75Hz, IPS Panel',
                'responsible_person' => 'Bagian Umum',
            ],

            // Keyboard & Mouse (Peralatan IT)
            [
                'name' => 'Keyboard Logitech K120',
                'category_code' => 'KBM',
                'location_code' => 'RLJ',
                'brand' => 'Logitech',
                'model' => 'K120 USB Keyboard',
                'serial_number' => 'LG2024007',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Aksesoris Komputer',
                'quantity' => 15,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 150000,
                'specifications' => 'USB Keyboard, Full-size, Quiet keys, Spill-resistant',
                'responsible_person' => 'Bagian Umum',
            ],
            // Kertas A4 (Alat Tulis Kantor)
            [
                'name' => 'Kertas A4 80gsm',
                'category_code' => 'KTR',
                'location_code' => 'RAK',
                'brand' => 'PaperOne',
                'model' => 'A4 Premium',
                'serial_number' => 'PO2024008',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Kertas Indonesia',
                'quantity' => 50,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 45000,
                'specifications' => 'A4 80gsm, 500 sheets/rim, ISO 9001 certified',
                'responsible_person' => 'Sekretaris Direktur',
            ],

            // Pulpen (Alat Tulis Kantor)
            [
                'name' => 'Pulpen Pilot V5',
                'category_code' => 'PPN',
                'location_code' => 'RAK',
                'brand' => 'Pilot',
                'model' => 'V5 Ballpoint',
                'serial_number' => 'PL2024009',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Alat Tulis Sukses',
                'quantity' => 100,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 8500,
                'specifications' => '0.5mm tip, Black ink, Retractable ballpoint pen',
                'responsible_person' => 'Bagian Umum',
            ],
            // Map Plastik (Alat Tulis Kantor)
            [
                'name' => 'Map Plastik Klarus',
                'category_code' => 'MAP',
                'location_code' => 'RRP',
                'brand' => 'Klarus',
                'model' => 'Map Plastik A4',
                'serial_number' => 'KP2024010',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Map Plastik',
                'quantity' => 25,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 3500,
                'specifications' => 'A4 size, Clear plastic, Button closure, 20 sheets capacity',
                'notes' => null,
                'responsible_person' => 'Bagian Umum',
            ],

            // CCTV (Elektronik Kantor)
            [
                'name' => 'CCTV Dome 2MP',
                'category_code' => 'PRS',
                'location_code' => 'RPB',
                'brand' => 'Hikvision',
                'model' => 'DS-2CE12DFT-F',
                'serial_number' => 'CV2024011',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Security System',
                'quantity' => 8,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 650000,
                'specifications' => '2MP, 1920x1080, IR 30m, Indoor dome camera',
                'responsible_person' => 'IT Support',
            ],
            [
                'name' => 'DVR 8 Channel',
                'category_code' => 'PRS',
                'location_code' => 'RPB',
                'brand' => 'Hikvision',
                'model' => 'DS-7208HUHI-K1',
                'serial_number' => 'CV2024012',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Security System',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 1800000,
                'specifications' => '8 Channel, 5MP, 1 SATA up to 6TB, H.265+',
                'responsible_person' => 'IT Support',
            ],

            // Proyektor (Elektronik Kantor)
            [
                'name' => 'Proyektor Epson EB-X06',
                'category_code' => 'PRJ',
                'location_code' => 'ALI',
                'brand' => 'Epson',
                'model' => 'EB-X06',
                'serial_number' => 'PJ2024013',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Presentasi Sukses',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 6800000,
                'specifications' => 'XGA (1024x768), 3200 lumens, 3LCD, HDMI/USB',
                'responsible_person' => 'Sekretaris Direktur',
            ],

            // Galon Air Minum (Peralatan Rumah Tangga)
            [
                'name' => 'Galon Air Mineral',
                'category_code' => 'GLN',
                'location_code' => 'REP',
                'brand' => 'Aqua',
                'model' => 'Galon 19L',
                'serial_number' => 'AQ2024014',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Air Minum Sehat',
                'quantity' => 20,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 15000,
                'specifications' => '19 Liter, Food grade plastic, Mineral water',
                'responsible_person' => 'Bagian Umum',
            ],

            // Hardisk Eksternal (Peralatan IT)
            [
                'name' => 'Hardisk Eksternal WD 1TB',
                'category_code' => 'HDD',
                'location_code' => 'RAP',
                'brand' => 'WD',
                'model' => 'My Passport 1TB',
                'serial_number' => 'WD2024015',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Storage Indonesia',
                'quantity' => 5,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 750000,
                'specifications' => '1TB USB 3.0, Portable external hard drive, Backup software',
                'responsible_person' => 'Bagian Umum',
            ],

            // Mobil Dinas (Kendaraan Operasional)
            [
                'name' => 'Mobil Dinas Toyota Avanza',
                'category_code' => 'MOB',
                'location_code' => 'GBK',
                'brand' => 'Toyota',
                'model' => 'Avanza 1.3 Veloz',
                'serial_number' => 'MHKM2024016',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Auto Sejahtera',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 245000000,
                'specifications' => '1300cc, 7 seater, manual transmission, white color',
                'notes' => 'Mobil dinas untuk operasional lapangan',
                'responsible_person' => 'Bagian Umum',
            ],

            // Alat Tulis Kantor (ATK)
            [
                'name' => 'Pulpen Pilot V5',
                'category_code' => 'ATK',
                'location_code' => 'GB',
                'brand' => 'Pilot',
                'model' => 'V5 Hi-Tecpoint',
                'serial_number' => null,
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Alat Tulis Sukses',
                'quantity' => 100,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 15000,
                'specifications' => '0.5mm point size, black ink, retractable',
                'notes' => 'Stok ATK untuk kebutuhan kantor',
                'responsible_person' => 'Bagian Umum',
            ],
            [
                'name' => 'Kertas A4 80gsm',
                'category_code' => 'ATK',
                'location_code' => 'GB',
                'brand' => 'PaperOne',
                'model' => 'A4 80gsm',
                'serial_number' => null,
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Kertas Murah',
                'quantity' => 50,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 45000,
                'specifications' => 'A4 size, 80gsm, 500 sheets/ream, white',
                'notes' => 'Stok kertas untuk printer dan fotokopi',
                'responsible_person' => 'Bagian Umum',
            ],
        ];

        foreach ($commodities as $commodityData) {
            // Get category and location IDs
            $category = $categories->get($commodityData['category_code']);
            $location = $locations->get($commodityData['location_code']);

            if (!$category || !$location) {
                $this->command->warn("Skipping {$commodityData['name']}: Missing category or location");
                continue;
            }

            // Prepare data for creation
            $data = [
                'name' => $commodityData['name'],
                'category_id' => $category->id,
                'location_id' => $location->id,
                'brand' => $commodityData['brand'],
                'model' => $commodityData['model'],
                'serial_number' => $commodityData['serial_number'],
                'acquisition_type' => $commodityData['acquisition_type'],
                'acquisition_source' => $commodityData['acquisition_source'],
                'quantity' => $commodityData['quantity'],
                'condition' => $commodityData['condition'],
                'purchase_year' => $commodityData['purchase_year'],
                'purchase_price' => $commodityData['purchase_price'],
                'specifications' => $commodityData['specifications'],
                'notes' => $commodityData['notes'] ?? null,
                'responsible_person' => $commodityData['responsible_person'],
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ];

            // Let the model handle item_code generation
            Commodity::create($data);
        }

        $this->command->info('✅ Commodities seeded successfully');
        $this->command->info('   Created ' . count($commodities) . ' sample commodities');
        $this->command->info('   Covers all categories and various conditions');
    }
}
