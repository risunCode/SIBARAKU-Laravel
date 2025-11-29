<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Transfer;

class TransferObserver
{
    public function created(Transfer $transfer): void
    {
        ActivityLog::log(
            'created',
            "Membuat transfer {$transfer->transfer_number}",
            $transfer
        );
    }

    public function updated(Transfer $transfer): void
    {
        if ($transfer->wasChanged('status')) {
            $status = match($transfer->status) {
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'completed' => 'Selesai',
                default => $transfer->status,
            };

            ActivityLog::log(
                'updated',
                "Status transfer {$transfer->transfer_number} diubah menjadi: {$status}",
                $transfer,
                ['status' => $transfer->getOriginal('status')],
                ['status' => $transfer->status]
            );
        } else {
            ActivityLog::log(
                'updated',
                "Memperbarui transfer {$transfer->transfer_number}",
                $transfer
            );
        }
    }

    public function deleted(Transfer $transfer): void
    {
        ActivityLog::log(
            'deleted',
            "Menghapus transfer {$transfer->transfer_number}"
        );
    }
}
