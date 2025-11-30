<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'commodity_id',
        'from_location_id',
        'to_location_id',
        'requested_by',
        'approved_by',
        'status',
        'reason',
        'rejection_reason',
        'transfer_date',
        'notes',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate transfer_number
        static::creating(function ($model) {
            if (empty($model->transfer_number)) {
                $model->transfer_number = self::generateTransferNumber();
            }
        });
    }

    /**
     * Generate nomor transfer otomatis.
     * Format: TRF-[TAHUN][BULAN]-[URUT 4 DIGIT]
     */
    public static function generateTransferNumber(): string
    {
        $yearMonth = date('Ym');
        $prefix = "TRF-{$yearMonth}-";

        $lastItem = self::where('transfer_number', 'like', $prefix . '%')
            ->orderBy('transfer_number', 'desc')
            ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem->transfer_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Barang yang ditransfer.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    /**
     * Lokasi asal.
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    /**
     * Lokasi tujuan.
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    /**
     * User yang mengajukan.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * User yang menyetujui/menolak.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Tanda tangan.
     */
    public function signature(): HasOne
    {
        return $this->hasOne(ReportSignature::class, 'signable_id')->where('signable_type', 'transfer');
    }

    /**
     * Scope untuk status tertentu.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get label status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Get badge class untuk status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-info',
            'rejected' => 'badge-danger',
            'completed' => 'badge-success',
            'cancelled' => 'badge-gray',
            default => 'badge-gray',
        };
    }

    /**
     * Cek apakah bisa diapprove.
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah bisa direject.
     */
    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah bisa dicomplete.
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'approved';
    }
}
