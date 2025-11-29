<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'disposal_number',
        'commodity_id',
        'disposal_date',
        'reason',
        'disposal_method',
        'description',
        'estimated_value',
        'disposal_value',
        'notes',
        'requested_by',
        'approved_by',
        'status',
        'rejection_reason',
        'attachments',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'estimated_value' => 'decimal:2',
        'disposal_value' => 'decimal:2',
        'attachments' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate disposal_number
        static::creating(function ($model) {
            if (empty($model->disposal_number)) {
                $model->disposal_number = self::generateDisposalNumber();
            }
        });
    }

    /**
     * Generate nomor disposal otomatis.
     * Format: DSP-[TAHUN][BULAN]-[URUT 4 DIGIT]
     */
    public static function generateDisposalNumber(): string
    {
        $yearMonth = date('Ym');
        $prefix = "DSP-{$yearMonth}-";

        $lastItem = self::where('disposal_number', 'like', $prefix . '%')
            ->orderBy('disposal_number', 'desc')
            ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem->disposal_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Barang yang dihapuskan.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
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
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-gray',
        };
    }

    /**
     * Get label alasan.
     */
    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
            'usang' => 'Usang / Tidak Layak',
            'dicuri' => 'Dicuri',
            'dijual' => 'Dijual',
            'dihibahkan' => 'Dihibahkan',
            'lainnya' => 'Lainnya',
            default => $this->reason,
        };
    }

    /**
     * Get nilai disposal format Rupiah.
     */
    public function getFormattedValueAttribute(): string
    {
        return 'Rp ' . number_format($this->estimated_value ?? 0, 0, ',', '.');
    }

    /**
     * Get disposal value format Rupiah.
     */
    public function getFormattedDisposalValueAttribute(): string
    {
        return 'Rp ' . number_format($this->disposal_value ?? 0, 0, ',', '.');
    }

    /**
     * Get label metode disposal.
     */
    public function getDisposalMethodLabelAttribute(): ?string
    {
        if (!$this->disposal_method) {
            return null;
        }

        return match ($this->disposal_method) {
            'auction' => 'Lelang',
            'donation' => 'Hibah',
            'destruction' => 'Pemusnahan',
            'sale' => 'Penjualan Langsung',
            'recycle' => 'Daur Ulang',
            default => $this->disposal_method,
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
}
