<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commodity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_code',
        'name',
        'category_id',
        'location_id',
        'brand',
        'model',
        'serial_number',
        'acquisition_type',
        'acquisition_source',
        'quantity',
        'condition',
        'purchase_year',
        'purchase_price',
        'specifications',
        'notes',
        'responsible_person',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'quantity' => 'integer',
        'purchase_year' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate item_code jika kosong
        static::creating(function ($model) {
            if (empty($model->item_code)) {
                $model->item_code = self::generateItemCode();
            }
        });
    }

    /**
     * Generate kode barang otomatis.
     * Format: INV-[TAHUN]-[URUT 4 DIGIT]
     */
    public static function generateItemCode(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        $lastItem = self::withTrashed()
            ->where('item_code', 'like', $prefix . '%')
            ->orderBy('item_code', 'desc')
            ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem->item_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Category barang.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Lokasi barang.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * User yang membuat.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User yang terakhir update.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Foto-foto barang.
     */
    public function images(): HasMany
    {
        return $this->hasMany(CommodityImage::class)->orderBy('sort_order');
    }

    /**
     * Foto utama barang.
     */
    public function primaryImage()
    {
        return $this->hasOne(CommodityImage::class)->where('is_primary', true);
    }

    /**
     * Riwayat transfer.
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * Riwayat maintenance.
     */
    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    /**
     * Riwayat disposal.
     */
    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class);
    }

    /**
     * Scope untuk kondisi tertentu.
     */
    public function scopeCondition($query, string $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope untuk barang kondisi baik.
     */
    public function scopeGoodCondition($query)
    {
        return $query->where('condition', 'baik');
    }

    /**
     * Get label kondisi.
     */
    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->condition,
        };
    }

    /**
     * Get label tipe perolehan.
     */
    public function getAcquisitionTypeLabelAttribute(): string
    {
        return match ($this->acquisition_type) {
            'pembelian' => 'Pembelian',
            'hibah' => 'Hibah',
            'bantuan' => 'Bantuan',
            'produksi' => 'Produksi Sendiri',
            'lainnya' => 'Lainnya',
            default => $this->acquisition_type,
        };
    }

    /**
     * Get badge class untuk kondisi.
     */
    public function getConditionBadgeClassAttribute(): string
    {
        return match ($this->condition) {
            'baik' => 'badge-success',
            'rusak_ringan' => 'badge-warning',
            'rusak_berat' => 'badge-danger',
            default => 'badge-gray',
        };
    }

    /**
     * Get URL foto utama atau default.
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->primaryImage;

        if ($primary) {
            return asset('storage/' . $primary->image_path);
        }

        $first = $this->images->first();
        if ($first) {
            return asset('storage/' . $first->image_path);
        }

        return asset('images/no-image.png');
    }

    /**
     * Get harga format Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }
}
