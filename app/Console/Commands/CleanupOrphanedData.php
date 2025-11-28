<?php

namespace App\Console\Commands;

use App\Models\Transfer;
use App\Models\Disposal;
use App\Models\Maintenance;
use Illuminate\Console\Command;

class CleanupOrphanedData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:cleanup-orphaned {--dry-run : Preview what would be deleted without actually deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up transfers, disposals, and maintenance records that reference deleted commodities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN - No data will be actually deleted');
            $this->line('');
        } else {
            $this->warn('⚠️  LIVE RUN - Data will be permanently deleted!');
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
            $this->line('');
        }

        $this->cleanupTransfers($isDryRun);
        $this->cleanupDisposals($isDryRun);
        $this->cleanupMaintenance($isDryRun);

        $this->line('');
        $this->info('✅ Cleanup completed!');
        
        if ($isDryRun) {
            $this->info('💡 Run without --dry-run flag to actually delete the data');
        }

        return 0;
    }

    /**
     * Clean up orphaned transfers.
     */
    private function cleanupTransfers(bool $isDryRun): void
    {
        $this->info('🔄 Checking transfers...');
        
        // Find transfers with null commodity_id or non-existent commodities
        $orphanedTransfers = Transfer::whereDoesntHave('commodity')->get();
        
        $count = $orphanedTransfers->count();
        
        if ($count === 0) {
            $this->line('   ✅ No orphaned transfers found');
            return;
        }

        $this->warn("   Found {$count} orphaned transfers:");
        
        foreach ($orphanedTransfers as $transfer) {
            $this->line("   - Transfer #{$transfer->transfer_number} (ID: {$transfer->id})");
        }

        if (!$isDryRun) {
            $orphanedTransfers->each->delete();
            $this->info("   🗑️  Deleted {$count} orphaned transfers");
        }
    }

    /**
     * Clean up orphaned disposals.
     */
    private function cleanupDisposals(bool $isDryRun): void
    {
        $this->info('🔄 Checking disposals...');
        
        $orphanedDisposals = Disposal::whereDoesntHave('commodity')->get();
        
        $count = $orphanedDisposals->count();
        
        if ($count === 0) {
            $this->line('   ✅ No orphaned disposals found');
            return;
        }

        $this->warn("   Found {$count} orphaned disposals:");
        
        foreach ($orphanedDisposals as $disposal) {
            $this->line("   - Disposal #{$disposal->disposal_number} (ID: {$disposal->id})");
        }

        if (!$isDryRun) {
            $orphanedDisposals->each->delete();
            $this->info("   🗑️  Deleted {$count} orphaned disposals");
        }
    }

    /**
     * Clean up orphaned maintenance records.
     */
    private function cleanupMaintenance(bool $isDryRun): void
    {
        $this->info('🔄 Checking maintenance records...');
        
        $orphanedMaintenance = Maintenance::whereDoesntHave('commodity')->get();
        
        $count = $orphanedMaintenance->count();
        
        if ($count === 0) {
            $this->line('   ✅ No orphaned maintenance records found');
            return;
        }

        $this->warn("   Found {$count} orphaned maintenance records:");
        
        foreach ($orphanedMaintenance as $maintenance) {
            $this->line("   - Maintenance ID: {$maintenance->id} (Commodity ID: {$maintenance->commodity_id})");
        }

        if (!$isDryRun) {
            $orphanedMaintenance->each->delete();
            $this->info("   🗑️  Deleted {$count} orphaned maintenance records");
        }
    }
}
