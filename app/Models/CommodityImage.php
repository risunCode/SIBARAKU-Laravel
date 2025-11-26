<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CommodityImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_id',
        'image_path',
        'original_name',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Commodity pemilik gambar.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    /**
     * Get URL gambar.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get thumbnail URL (jika ada).
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Jika menggunakan thumbnail terpisah
        $thumbnailPath = str_replace('commodities/', 'commodities/thumbnails/', $this->image_path);

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }

        return $this->url;
    }

    /**
     * Delete file saat model dihapus.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            Storage::disk('public')->delete($model->image_path);
        });
    }
}
