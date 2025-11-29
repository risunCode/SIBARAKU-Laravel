<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Location;

class LocationObserver
{
    public function created(Location $location): void
    {
        ActivityLog::log(
            'created',
            "Membuat lokasi '{$location->name}'",
            $location
        );
    }

    public function updated(Location $location): void
    {
        ActivityLog::log(
            'updated',
            "Memperbarui lokasi '{$location->name}'",
            $location
        );
    }

    public function deleted(Location $location): void
    {
        ActivityLog::log(
            'deleted',
            "Menghapus lokasi '{$location->name}'"
        );
    }

    public function restored(Location $location): void
    {
        ActivityLog::log(
            'restored',
            "Memulihkan lokasi '{$location->name}'",
            $location
        );
    }
}
