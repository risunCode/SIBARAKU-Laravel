<?php

namespace Database\MigrationsDemo;

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
                'name' => 'Gedung Pengadaan Barang',
                'code' => 'GPB',
                'description' => 'Gedung Utama Pengadaan Barang dan Jasa Kominfo',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 1',
                'room' => 'Ruang Pelayanan',
            ],
            [
                'name' => 'Ruang Layanan Pengadaan Jasa',
                'code' => 'RLJ',
                'description' => 'Ruang Layanan Pengadaan Jasa Kominfo',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 2',
                'room' => 'R.201',
            ],
            [
                'name' => 'Ruang Evaluasi Pengadaan',
                'code' => 'REP',
                'description' => 'Ruang Evaluasi dan Penilaian Pengadaan',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 2',
                'room' => 'R.202',
            ],
            [
                'name' => 'Ruang Administrasi Kontrak',
                'code' => 'RAK',
                'description' => 'Ruang Administrasi Kontrak Pengadaan',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 3',
                'room' => 'R.301',
            ],
            [
                'name' => 'Ruang Pemeriksaan Barang',
                'code' => 'RPB',
                'description' => 'Ruang Pemeriksaan dan Penerimaan Barang',
                'building' => 'Gedung Gudang',
                'floor' => 'Lantai 1',
                'room' => 'Area Pemeriksaan',
            ],
            [
                'name' => 'Gudang Barang IT',
                'code' => 'GBI',
                'description' => 'Gudang Penyimpanan Barang Peralatan IT',
                'building' => 'Gedung Gudang',
                'floor' => 'Lantai 1',
                'room' => 'Gudang A',
            ],
            [
                'name' => 'Gudang Barang Kantor',
                'code' => 'GBK',
                'description' => 'Gudang Penyimpanan Barang Perlengkapan Kantor',
                'building' => 'Gedung Gudang',
                'floor' => 'Lantai 2',
                'room' => 'Gudang B',
            ],
            [
                'name' => 'Ruang Arsip Pengadaan',
                'code' => 'RAP',
                'description' => 'Ruang Arsip Dokumen Pengadaan',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 1',
                'room' => 'R.101',
            ],
            [
                'name' => 'Ruang Rapat Panitia',
                'code' => 'RRP',
                'description' => 'Ruang Rapat Panitia Pengadaan',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 3',
                'room' => 'Ruang Rapat Utama',
            ],
            [
                'name' => 'Area Layanan Informasi',
                'code' => 'ALI',
                'description' => 'Area Layanan Informasi Pengadaan',
                'building' => 'Gedung Pengadaan',
                'floor' => 'Lantai 1',
                'room' => 'Lobby Utama',
            ],
        ];

        foreach ($locations as $location) {
            // Check if location already exists
            $existingLocation = Location::where('code', $location['code'])->first();
            
            if (!$existingLocation) {
                Location::create($location);
            }
        }
    }
}
