<?php

namespace Database\Seeders\Demo;

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
            // Laptop (Elektronik - Komputer & Laptop)
            [
                'name' => 'Laptop Dell Inspiron 15',
                'category_code' => 'KOM',
                'location_code' => 'R-KRJ1',
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
                'category_code' => 'KOM',
                'location_code' => 'R-KRJ2',
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
            
            // Printer (Elektronik - Printer & Scanner)
            [
                'name' => 'Printer Canon PIXMA G3010',
                'category_code' => 'PRT',
                'location_code' => 'R-RPT1',
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
                'category_code' => 'PRT',
                'location_code' => 'R-KRJ1',
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

            // Meja (Mebel & Furnitur - Meja)
            [
                'name' => 'Meja Kerja Staff',
                'category_code' => 'MJA',
                'location_code' => 'R-KRJ1',
                'brand' => 'Furniture Indonesia',
                'model' => 'Executive Desk 120',
                'serial_number' => 'FR2024005',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Meja Kerja',
                'quantity' => 10,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 1200000,
                'specifications' => 'Material: Particle board, Size: 120x60x75cm, Color: Brown',
                'responsible_person' => 'Bagian Umum',
            ],
            [
                'name' => 'Meja Rapat',
                'category_code' => 'MJA',
                'location_code' => 'R-RPT1',
                'brand' => 'Furniture Indonesia',
                'model' => 'Meeting Table 240',
                'serial_number' => 'FR2024006',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Meja Kerja',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 3500000,
                'specifications' => 'Material: Solid wood, Size: 240x120x75cm, Seats 8 persons',
                'responsible_person' => 'Bagian Umum',
            ],

            // Kursi (Mebel & Furnitur - Kursi)
            [
                'name' => 'Kursi Staff Ergonomic',
                'category_code' => 'KRS',
                'location_code' => 'R-KRJ1',
                'brand' => 'Chair Comfort',
                'model' => 'Ergo-2000',
                'serial_number' => 'CH2024007',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Kursi Nyaman',
                'quantity' => 10,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 850000,
                'specifications' => 'Ergonomic chair with lumbar support, armrests, breathable mesh',
                'responsible_person' => 'Bagian Umum',
            ],
            [
                'name' => 'Kursi Direktur',
                'category_code' => 'KRS',
                'location_code' => 'R-KEP',
                'brand' => 'Executive Chair',
                'model' => 'Lux-100',
                'serial_number' => 'CH2024008',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Kursi Executive',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 2500000,
                'specifications' => 'High-back executive chair, genuine leather, adjustable height',
                'responsible_person' => 'Sekretaris Direktur',
            ],

            // AC (Elektronik - AC & Pendingin)
            [
                'name' => 'AC Split 1 PK',
                'category_code' => 'ACC',
                'location_code' => 'R-KEP',
                'brand' => 'Panasonic',
                'model' => 'CS/CU-YN5TKJ',
                'serial_number' => 'AC2024009',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. AC Sejuk',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 4200000,
                'specifications' => '1 PK, 320W, R32 refrigerant, low voltage operation',
                'responsible_person' => 'Bagian Umum',
            ],
            [
                'name' => 'AC Split 2 PK',
                'category_code' => 'ACC',
                'location_code' => 'R-RPT1',
                'brand' => 'LG',
                'model' => 'S12AWC-FA',
                'serial_number' => 'AC2024010',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. AC Dingin',
                'quantity' => 1,
                'condition' => 'rusak_berat',
                'purchase_year' => 2023,
                'purchase_price' => 5800000,
                'specifications' => '2 PK, 640W, R410A refrigerant, jet cool function',
                'notes' => 'Perlu service compressor, tidak dingin',
                'responsible_person' => 'Bagian Umum',
            ],

            // CCTV (Elektronik - CCTV & Keamanan)
            [
                'name' => 'CCTV Dome 2MP',
                'category_code' => 'CTV',
                'location_code' => 'R-SRV',
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
                'category_code' => 'CTV',
                'location_code' => 'R-SRV',
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

            // Telepon (Elektronik - Telepon & Fax)
            [
                'name' => 'Telepon Cordless Panasonic',
                'category_code' => 'TEL',
                'location_code' => 'R-SEK',
                'brand' => 'Panasonic',
                'model' => 'KX-TGC210',
                'serial_number' => 'TL2024013',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Telekomunikasi',
                'quantity' => 2,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 450000,
                'specifications' => 'Cordless phone, 1.8" LCD, 50 name phonebook, Eco mode',
                'responsible_person' => 'Sekretaris Direktur',
            ],

            // Proyektor (Elektronik - Peralatan Presentasi)
            [
                'name' => 'Proyektor Epson EB-X06',
                'category_code' => 'PRE',
                'location_code' => 'R-RPT1',
                'brand' => 'Epson',
                'model' => 'EB-X06',
                'serial_number' => 'PJ2024014',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Presentasi Sukses',
                'quantity' => 1,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 6800000,
                'specifications' => 'XGA (1024x768), 3200 lumens, 3LCD, HDMI/USB',
                'responsible_person' => 'Bagian Umum',
            ],

            // Lemari (Mebel & Furnitur - Lemari & Rak)
            [
                'name' => 'Lemari Arsip 4 Laci',
                'category_code' => 'LMR',
                'location_code' => 'R-ARS',
                'brand' => 'Furniture Indonesia',
                'model' => 'Filing Cabinet 4D',
                'serial_number' => 'LM2024015',
                'acquisition_type' => 'pembelian',
                'acquisition_source' => 'PT. Lemari Arsip',
                'quantity' => 3,
                'condition' => 'baik',
                'purchase_year' => 2024,
                'purchase_price' => 1500000,
                'specifications' => '4 drawers, metal material, 40x52x132cm, lockable',
                'responsible_person' => 'Bagian Umum',
            ],

            // Mobil (Kendaraan - Mobil)
            [
                'name' => 'Mobil Operasional Toyota Avanza',
                'category_code' => 'MOB',
                'location_code' => 'AULA',
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
                'location_code' => 'GDG',
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
                'location_code' => 'GDG',
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
