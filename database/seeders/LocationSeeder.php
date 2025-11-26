<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Ruang Kepala',
                'code' => 'R-KEP',
                'description' => 'Ruang Kepala Kantor',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'R.201',
            ],
            [
                'name' => 'Ruang Sekretaris',
                'code' => 'R-SEK',
                'description' => 'Ruang Sekretaris',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'R.202',
            ],
            [
                'name' => 'Ruang Rapat Utama',
                'code' => 'R-RPT1',
                'description' => 'Ruang Rapat Utama',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'R.203',
            ],
            [
                'name' => 'Ruang Kerja Lantai 1',
                'code' => 'R-KRJ1',
                'description' => 'Ruang Kerja Staff Lantai 1',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 1',
                'room' => 'R.101',
            ],
            [
                'name' => 'Ruang Kerja Lantai 2',
                'code' => 'R-KRJ2',
                'description' => 'Ruang Kerja Staff Lantai 2',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 2',
                'room' => 'R.204',
            ],
            [
                'name' => 'Ruang Server',
                'code' => 'R-SRV',
                'description' => 'Ruang Server dan IT',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 1',
                'room' => 'R.102',
            ],
            [
                'name' => 'Gudang',
                'code' => 'GDG',
                'description' => 'Gudang Penyimpanan',
                'building' => 'Gedung Belakang',
                'floor' => 'Lantai 1',
                'room' => null,
            ],
            [
                'name' => 'Resepsionis',
                'code' => 'R-RSP',
                'description' => 'Area Resepsionis',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 1',
                'room' => null,
            ],
            [
                'name' => 'Ruang Arsip',
                'code' => 'R-ARS',
                'description' => 'Ruang Penyimpanan Arsip',
                'building' => 'Gedung Utama',
                'floor' => 'Lantai 1',
                'room' => 'R.103',
            ],
            [
                'name' => 'Aula',
                'code' => 'AULA',
                'description' => 'Aula Pertemuan',
                'building' => 'Gedung Belakang',
                'floor' => 'Lantai 2',
                'room' => null,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
