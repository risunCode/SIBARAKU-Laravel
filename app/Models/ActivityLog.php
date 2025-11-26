<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * User yang melakukan aksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Model yang terkena aksi.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Log aktivitas.
     */
    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Scope untuk action tertentu.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk model tertentu.
     */
    public function scopeForModel($query, Model $model)
    {
        return $query->where('model_type', get_class($model))
            ->where('model_id', $model->id);
    }

    /**
     * Scope untuk user tertentu.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get label action.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Membuat',
            'updated' => 'Mengubah',
            'deleted' => 'Menghapus',
            'restored' => 'Memulihkan',
            'login' => 'Login',
            'logout' => 'Logout',
            'approved' => 'Menyetujui',
            'rejected' => 'Menolak',
            'transferred' => 'Transfer',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get badge class untuk action.
     */
    public function getActionBadgeClassAttribute(): string
    {
        return match ($this->action) {
            'created' => 'badge-success',
            'updated' => 'badge-info',
            'deleted' => 'badge-danger',
            'restored' => 'badge-primary',
            'login' => 'badge-success',
            'logout' => 'badge-gray',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-gray',
        };
    }

    /**
     * Get nama model yang readable.
     */
    public function getModelNameAttribute(): ?string
    {
        if (!$this->model_type) {
            return null;
        }

        $className = class_basename($this->model_type);

        return match ($className) {
            'Commodity' => 'Barang',
            'Category' => 'Kategori',
            'Location' => 'Lokasi',
            'Transfer' => 'Transfer',
            'Disposal' => 'Penghapusan',
            'MaintenanceLog' => 'Maintenance',
            'User' => 'User',
            default => $className,
        };
    }
}
