<?php

namespace App\Console\Commands;

use App\Models\Commodity;
use App\Models\Disposal;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Console\Command;

class CreateTestDisposal extends Command
{
    protected $signature = 'app:create-test-data';
    protected $description = 'Create test disposal and maintenance data';

    public function handle()
    {
        $commodity = Commodity::first();
        $user = User::first();

        if (!$commodity || !$user) {
            $this->error('No commodity or user found. Run demo seeder first.');
            return 1;
        }

        // Create disposal if less than 2 exist
        if (Disposal::count() < 2) {
            $reasons = ['rusak_berat', 'usang', 'hilang'];
            $descriptions = [
                'Barang sudah tidak bisa dipakai dan perlu dihapuskan dari inventaris.',
                'Barang sudah usang dan tidak layak pakai.',
                'Barang hilang dan tidak dapat ditemukan.'
            ];
            
            for ($i = Disposal::count(); $i < 2; $i++) {
                Disposal::create([
                    'commodity_id' => $commodity->id,
                    'reason' => $reasons[$i],
                    'description' => $descriptions[$i],
                    'requested_by' => $user->id,
                    'status' => 'pending',
                    'disposal_date' => now(),
                ]);
                $this->info('✅ Test disposal ' . ($i + 1) . ' created!');
            }
        } else {
            $this->info('Disposals already exist.');
        }

        // Create maintenance if none exists
        if (Maintenance::count() === 0) {
            Maintenance::create([
                'commodity_id' => $commodity->id,
                'maintenance_date' => now(),
                'maintenance_type' => 'Perbaikan',
                'description' => 'Perbaikan rutin dan pengecekan kondisi barang.',
                'cost' => 150000,
                'performed_by' => 'Teknisi IT',
                'condition_after' => 'baik',
                'created_by' => $user->id,
            ]);
            $this->info('✅ Test maintenance created!');
        } else {
            $this->info('Maintenance already exists.');
        }

        return 0;
    }
}
