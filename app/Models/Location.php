<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'pic',
        'building',
        'floor',
        'room',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Commodities di lokasi ini.
     */
    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }

    /**
     * Transfers dari lokasi ini.
     */
    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_location_id');
    }

    /**
     * Transfers ke lokasi ini.
     */
    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_location_id');
    }

    /**
     * Scope untuk lokasi aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get nama lengkap lokasi.
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->building, $this->floor, $this->room]);

        if (empty($parts)) {
            return $this->name;
        }

        return $this->name . ' (' . implode(', ', $parts) . ')';
    }

    /**
     * Get jumlah barang di lokasi ini.
     */
    public function getCommoditiesCountAttribute(): int
    {
        return $this->commodities()->count();
    }
}
