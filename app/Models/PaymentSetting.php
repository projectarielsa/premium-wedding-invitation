<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Payment settings model for bank accounts and payment methods.
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $account_number
 * @property string|null $account_name
 * @property string|null $logo
 * @property string|null $qr_code_image
 * @property string|null $instructions
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PaymentSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'account_number',
        'account_name',
        'logo',
        'qr_code_image',
        'instructions',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
        'sort_order' => 0,
    ];

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only active payment methods.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope for bank transfers.
     */
    public function scopeBankTransfer(Builder $query): Builder
    {
        return $query->where('type', 'bank_transfer');
    }

    /**
     * Scope for e-wallets.
     */
    public function scopeEWallet(Builder $query): Builder
    {
        return $query->where('type', 'e_wallet');
    }

    /**
     * Scope for QRIS.
     */
    public function scopeQris(Builder $query): Builder
    {
        return $query->where('type', 'qris');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }

        return asset('storage/' . $this->logo);
    }

    /**
     * Get QR code image URL.
     */
    public function getQrCodeImageUrlAttribute(): ?string
    {
        if (!$this->qr_code_image) {
            return null;
        }

        if (str_starts_with($this->qr_code_image, 'http')) {
            return $this->qr_code_image;
        }

        return asset('storage/' . $this->qr_code_image);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'bank_transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'qris' => 'QRIS',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'bank_transfer' => 'building-library',
            'e_wallet' => 'device-phone-mobile',
            'qris' => 'qr-code',
            default => 'credit-card',
        };
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Check if this is a bank transfer method.
     */
    public function isBankTransfer(): bool
    {
        return $this->type === 'bank_transfer';
    }

    /**
     * Check if this is an e-wallet method.
     */
    public function isEWallet(): bool
    {
        return $this->type === 'e_wallet';
    }

    /**
     * Check if this is QRIS.
     */
    public function isQris(): bool
    {
        return $this->type === 'qris';
    }

    /**
     * Get display info for payment instructions.
     */
    public function getDisplayInfo(): array
    {
        return [
            'type' => $this->type,
            'type_label' => $this->type_label,
            'name' => $this->name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'logo_url' => $this->logo_url,
            'qr_code_url' => $this->qr_code_image_url,
            'instructions' => $this->instructions,
        ];
    }

    /**
     * Get all active payment methods grouped by type.
     */
    public static function getGroupedActive(): array
    {
        return self::active()
            ->ordered()
            ->get()
            ->groupBy('type')
            ->toArray();
    }
}
