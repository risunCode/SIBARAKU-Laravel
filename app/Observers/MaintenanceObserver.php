<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Maintenance;

class MaintenanceObserver
{
    public function created(Maintenance $maintenance): void
    {
        $commodityName = $maintenance->commodity?->name ?? 'Unknown';
        
        ActivityLog::log(
            'created',
            "Membuat log maintenance untuk {$commodityName}",
            $maintenance
        );
    }

    public function updated(Maintenance $maintenance): void
    {
        $commodityName = $maintenance->commodity?->name ?? 'Unknown';
        
        ActivityLog::log(
            'updated',
            "Memperbarui log maintenance untuk {$commodityName}",
            $maintenance
        );
    }

    public function deleted(Maintenance $maintenance): void
    {
        $commodityName = $maintenance->commodity?->name ?? 'Unknown';
        
        ActivityLog::log(
            'deleted',
            "Menghapus log maintenance untuk {$commodityName}"
        );
    }
}
