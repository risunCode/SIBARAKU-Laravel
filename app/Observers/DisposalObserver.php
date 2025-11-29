<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Disposal;

class DisposalObserver
{
    public function created(Disposal $disposal): void
    {
        ActivityLog::log(
            'created',
            "Membuat pengajuan penghapusan {$disposal->disposal_number}",
            $disposal
        );
    }

    public function updated(Disposal $disposal): void
    {
        if ($disposal->wasChanged('status')) {
            $status = match($disposal->status) {
                'pending' => 'Menunggu Persetujuan',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $disposal->status,
            };

            ActivityLog::log(
                'updated',
                "Status penghapusan {$disposal->disposal_number} diubah menjadi: {$status}",
                $disposal,
                ['status' => $disposal->getOriginal('status')],
                ['status' => $disposal->status]
            );
        } else {
            ActivityLog::log(
                'updated',
                "Memperbarui penghapusan {$disposal->disposal_number}",
                $disposal
            );
        }
    }

    public function deleted(Disposal $disposal): void
    {
        ActivityLog::log(
            'deleted',
            "Menghapus pengajuan penghapusan {$disposal->disposal_number}"
        );
    }
}
