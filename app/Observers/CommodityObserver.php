<?php

namespace App\Observers;

use App\Models\Commodity;
use App\Models\Transfer;
use App\Models\Disposal;
use App\Models\Maintenance;

class CommodityObserver
{
    /**
     * Handle the Commodity "deleting" event.
     * Clean up related records when commodity is being deleted.
     */
    public function deleting(Commodity $commodity): void
    {
        // Cleaning up related records

        // Cancel pending transfers
        $pendingTransfers = Transfer::where('commodity_id', $commodity->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingTransfers as $transfer) {
            $transfer->update([
                'status' => 'cancelled',
                'notes' => ($transfer->notes ?? '') . "\n\n[SISTEM] Transfer dibatalkan karena barang dihapus pada " . now()->format('d/m/Y H:i')
            ]);
        }

        // Cancel pending disposals  
        $pendingDisposals = Disposal::where('commodity_id', $commodity->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingDisposals as $disposal) {
            $disposal->update([
                'status' => 'cancelled',
                'notes' => ($disposal->notes ?? '') . "\n\n[SISTEM] Penghapusan dibatalkan karena barang dihapus pada " . now()->format('d/m/Y H:i')
            ]);
        }

        // Delete completed/approved transfers (they're historical, can be removed)
        Transfer::where('commodity_id', $commodity->id)
            ->whereIn('status', ['completed', 'approved'])
            ->delete();

        // Delete completed/approved disposals
        Disposal::where('commodity_id', $commodity->id)
            ->whereIn('status', ['completed', 'approved'])
            ->delete();

        // Delete all maintenance records (they're tied to the commodity)
        Maintenance::where('commodity_id', $commodity->id)->delete();

        // Cleanup completed
    }

    /**
     * Handle the Commodity "restored" event.
     * This runs when a soft-deleted commodity is restored.
     */
    public function restored(Commodity $commodity): void
    {
        // Commodity restored successfully
        
        // Note: We don't restore deleted transfers/disposals/maintenance
        // because they might have been intentionally cleaned up
        // If needed, this can be implemented with a separate restoration process
    }
}
