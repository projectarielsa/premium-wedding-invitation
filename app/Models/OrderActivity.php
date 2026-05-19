<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order activity/audit log model.
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $old_value
 * @property string|null $new_value
 * @property string|null $description
 * @property array|null $metadata
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read Order $order
 * @property-read User|null $user
 */
class OrderActivity extends Model
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
        'description',
        'metadata',
        'ip_address',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (OrderActivity $activity): void {
            $activity->created_at = $activity->created_at ?? now();
        });
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the order this activity belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who performed this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Order dibuat',
            'status_changed' => 'Status berubah',
            'payment_uploaded' => 'Bukti bayar diupload',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Get action icon.
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created' => 'plus-circle',
            'status_changed' => 'arrow-path',
            'payment_uploaded' => 'document-arrow-up',
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'cancelled' => 'x-mark',
            'completed' => 'check-badge',
            default => 'information-circle',
        };
    }

    /**
     * Get action color.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created' => 'blue',
            'payment_uploaded' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            'completed' => 'emerald',
            default => 'gray',
        };
    }

    /**
     * Get performer name.
     */
    public function getPerformerNameAttribute(): string
    {
        return $this->user?->name ?? 'System';
    }
}
