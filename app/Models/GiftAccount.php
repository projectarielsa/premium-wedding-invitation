<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GiftAccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Gift account model for digital wedding gifts.
 *
 * @property int $id
 * @property int $invitation_id
 * @property GiftAccountType $type
 * @property string $provider
 * @property string|null $provider_logo
 * @property string|null $account_number
 * @property string $account_holder
 * @property string|null $qr_image
 * @property string|null $instructions
 * @property bool $is_active
 * @property int $sort_order
 * @property int $copy_count
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Invitation $invitation
 */
class GiftAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gift_accounts';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invitation_id',
        'type',
        'provider',
        'provider_logo',
        'account_number',
        'account_holder',
        'qr_image',
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
        'type' => GiftAccountType::class,
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'copy_count' => 'integer',
        'view_count' => 'integer',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'bank_transfer',
        'is_active' => true,
        'sort_order' => 0,
        'copy_count' => 0,
        'view_count' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the invitation this gift account belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }



    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only active accounts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType(Builder $query, GiftAccountType $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter bank transfers.
     */
    public function scopeBankTransfers(Builder $query): Builder
    {
        return $query->where('type', GiftAccountType::BankTransfer);
    }

    /**
     * Scope to filter e-wallets.
     */
    public function scopeEWallets(Builder $query): Builder
    {
        return $query->where('type', GiftAccountType::EWallet);
    }

    /**
     * Scope to filter QRIS.
     */
    public function scopeQris(Builder $query): Builder
    {
        return $query->where('type', GiftAccountType::Qris);
    }



    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * Get the type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return $this->type->icon();
    }

    /**
     * Get the QR image URL.
     */
    public function getQrImageUrlAttribute(): ?string
    {
        if (! $this->qr_image) {
            return null;
        }

        if (str_starts_with($this->qr_image, 'http')) {
            return $this->qr_image;
        }

        return asset('storage/' . $this->qr_image);
    }

    /**
     * Get the provider logo URL.
     */
    public function getProviderLogoUrlAttribute(): ?string
    {
        if (! $this->provider_logo) {
            return $this->getDefaultProviderLogo();
        }

        if (str_starts_with($this->provider_logo, 'http')) {
            return $this->provider_logo;
        }

        return asset('storage/' . $this->provider_logo);
    }

    /**
     * Get masked account number for display.
     */
    public function getMaskedAccountNumberAttribute(): ?string
    {
        if (! $this->account_number) {
            return null;
        }

        $length = strlen($this->account_number);
        if ($length <= 4) {
            return $this->account_number;
        }

        $visible = substr($this->account_number, -4);
        $masked = str_repeat('•', $length - 4);

        return $masked . $visible;
    }



    /**
     * Check if account requires account number.
     */
    public function getRequiresAccountNumberAttribute(): bool
    {
        return $this->type->requiresAccountNumber();
    }

    /**
     * Check if account supports QR image.
     */
    public function getSupportsQrImageAttribute(): bool
    {
        return $this->type->supportsQrImage();
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Increment copy count.
     */
    public function incrementCopyCount(): void
    {
        $this->increment('copy_count');
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Get default provider logo based on provider name.
     */
    protected function getDefaultProviderLogo(): ?string
    {
        $logoMap = [
            'bca' => '/images/banks/bca.png',
            'mandiri' => '/images/banks/mandiri.png',
            'bni' => '/images/banks/bni.png',
            'bri' => '/images/banks/bri.png',
            'gopay' => '/images/ewallets/gopay.png',
            'ovo' => '/images/ewallets/ovo.png',
            'dana' => '/images/ewallets/dana.png',
            'shopeepay' => '/images/ewallets/shopeepay.png',
            'qris' => '/images/qris.png',
        ];

        $key = strtolower($this->provider);

        return $logoMap[$key] ?? null;
    }

    /**
     * Get display info for the gift card.
     */
    public function getDisplayInfo(): array
    {
        return [
            'type' => $this->type_label,
            'provider' => $this->provider,
            'account_number' => $this->account_number,
            'account_holder' => $this->account_holder,
            'qr_image' => $this->qr_image_url,
            'instructions' => $this->instructions,
        ];
    }
}
