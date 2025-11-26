<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_id',
        'maintenance_date',
        'maintenance_type',
        'description',
        'cost',
        'performed_by',
        'vendor',
        'next_maintenance_date',
        'condition_after',
        'created_by',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Barang yang dimaintenance.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    /**
     * User yang membuat log.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk maintenance yang akan datang.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->orderBy('next_maintenance_date');
    }

    /**
     * Scope untuk maintenance overdue.
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->orderBy('next_maintenance_date');
    }

    /**
     * Get label kondisi setelah maintenance.
     */
    public function getConditionAfterLabelAttribute(): ?string
    {
        if (!$this->condition_after) {
            return null;
        }

        return match ($this->condition_after) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->condition_after,
        };
    }

    /**
     * Get biaya format Rupiah.
     */
    public function getFormattedCostAttribute(): string
    {
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }

    /**
     * Cek apakah maintenance sudah overdue.
     */
    public function isOverdue(): bool
    {
        if (!$this->next_maintenance_date) {
            return false;
        }

        return $this->next_maintenance_date->isPast();
    }
}
