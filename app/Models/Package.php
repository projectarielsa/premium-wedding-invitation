<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SupportLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Package/Pricing plan model.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $badge
 * @property float $price
 * @property float|null $original_price
 * @property string $currency
 * @property int $duration_days
 * @property int $max_invitations
 * @property int $max_guests_per_invitation
 * @property int $max_events_per_invitation
 * @property int $max_gift_accounts
 * @property int $max_gallery_images
 * @property bool $rsvp_enabled
 * @property bool $gift_enabled
 * @property bool $qr_checkin_enabled
 * @property bool $analytics_enabled
 * @property bool $custom_music_enabled
 * @property bool $custom_domain_enabled
 * @property bool $export_enabled
 * @property bool $whatsapp_blast_enabled
 * @property bool $guest_book_enabled
 * @property bool $countdown_enabled
 * @property bool $story_section_enabled
 * @property bool $remove_watermark
 * @property array|null $template_access
 * @property SupportLevel $support_level
 * @property int|null $support_response_hours
 * @property bool $is_active
 * @property bool $is_featured
 * @property int $sort_order
 * @property array|null $features_list
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Order> $orders
 */
class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'badge',
        'price',
        'original_price',
        'currency',
        'duration_days',
        'max_invitations',
        'max_guests_per_invitation',
        'max_events_per_invitation',
        'max_gift_accounts',
        'max_gallery_images',
        'rsvp_enabled',
        'gift_enabled',
        'qr_checkin_enabled',
        'analytics_enabled',
        'custom_music_enabled',
        'custom_domain_enabled',
        'export_enabled',
        'whatsapp_blast_enabled',
        'guest_book_enabled',
        'countdown_enabled',
        'story_section_enabled',
        'remove_watermark',
        'template_access',
        'support_level',
        'support_response_hours',
        'is_active',
        'is_featured',
        'sort_order',
        'features_list',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_invitations' => 'integer',
        'max_guests_per_invitation' => 'integer',
        'max_events_per_invitation' => 'integer',
        'max_gift_accounts' => 'integer',
        'max_gallery_images' => 'integer',
        'rsvp_enabled' => 'boolean',
        'gift_enabled' => 'boolean',
        'qr_checkin_enabled' => 'boolean',
        'analytics_enabled' => 'boolean',
        'custom_music_enabled' => 'boolean',
        'custom_domain_enabled' => 'boolean',
        'export_enabled' => 'boolean',
        'whatsapp_blast_enabled' => 'boolean',
        'guest_book_enabled' => 'boolean',
        'countdown_enabled' => 'boolean',
        'story_section_enabled' => 'boolean',
        'remove_watermark' => 'boolean',
        'template_access' => 'array',
        'support_level' => SupportLevel::class,
        'support_response_hours' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'features_list' => 'array',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'currency' => 'IDR',
        'duration_days' => 365,
        'max_invitations' => 1,
        'max_guests_per_invitation' => 100,
        'max_events_per_invitation' => 2,
        'max_gift_accounts' => 2,
        'max_gallery_images' => 10,
        'rsvp_enabled' => true,
        'gift_enabled' => false,
        'qr_checkin_enabled' => false,
        'analytics_enabled' => false,
        'custom_music_enabled' => false,
        'custom_domain_enabled' => false,
        'export_enabled' => false,
        'whatsapp_blast_enabled' => false,
        'guest_book_enabled' => true,
        'countdown_enabled' => true,
        'story_section_enabled' => false,
        'remove_watermark' => false,
        'support_level' => 'community',
        'is_active' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get users with this package.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'active_package_id');
    }

    /**
     * Get orders for this package.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only active packages.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only featured packages.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Scope to order by price ascending.
     */
    public function scopeCheapestFirst(Builder $query): Builder
    {
        return $query->orderBy('price');
    }

    /**
     * Scope for public display.
     */
    public function scopePublicDisplay(Builder $query): Builder
    {
        return $query->active()->ordered();
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Get formatted original price.
     */
    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if (!$this->original_price) {
            return null;
        }

        return 'Rp ' . number_format((float) $this->original_price, 0, ',', '.');
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    /**
     * Check if package has discount.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_percentage !== null && $this->discount_percentage > 0;
    }

    /**
     * Get duration in human readable format.
     */
    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_days >= 365) {
            $years = floor($this->duration_days / 365);
            return $years . ' ' . ($years > 1 ? 'tahun' : 'tahun');
        }

        if ($this->duration_days >= 30) {
            $months = floor($this->duration_days / 30);
            return $months . ' ' . ($months > 1 ? 'bulan' : 'bulan');
        }

        return $this->duration_days . ' hari';
    }

    /**
     * Get max invitations display text.
     */
    public function getMaxInvitationsDisplayAttribute(): string
    {
        if ($this->max_invitations >= 999) {
            return 'Unlimited';
        }

        return (string) $this->max_invitations;
    }

    /**
     * Get max guests display text.
     */
    public function getMaxGuestsDisplayAttribute(): string
    {
        if ($this->max_guests_per_invitation >= 99999) {
            return 'Unlimited';
        }

        return number_format($this->max_guests_per_invitation);
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Check if package is free.
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    /**
     * Check if template is accessible for this package.
     */
    public function canAccessTemplate(Template $template): bool
    {
        // If template is not premium, always accessible
        if (!$template->is_premium) {
            return true;
        }

        // No template_access defined means basic only
        if (empty($this->template_access)) {
            return false;
        }

        // 'all' means access to all templates
        if (in_array('all', $this->template_access)) {
            return true;
        }

        // Check if specific template slug is in the access list
        return in_array($template->slug, $this->template_access);
    }

    /**
     * Get accessible template slugs.
     */
    public function getAccessibleTemplateSlugs(): array
    {
        if (empty($this->template_access)) {
            return [];
        }

        if (in_array('all', $this->template_access)) {
            return ['all'];
        }

        return $this->template_access;
    }

    /**
     * Check if a feature is enabled.
     */
    public function hasFeature(string $feature): bool
    {
        $featureMap = [
            'rsvp' => $this->rsvp_enabled,
            'gift' => $this->gift_enabled,
            'qr_checkin' => $this->qr_checkin_enabled,
            'analytics' => $this->analytics_enabled,
            'custom_music' => $this->custom_music_enabled,
            'custom_domain' => $this->custom_domain_enabled,
            'export' => $this->export_enabled,
            'whatsapp_blast' => $this->whatsapp_blast_enabled,
            'guest_book' => $this->guest_book_enabled,
            'countdown' => $this->countdown_enabled,
            'story_section' => $this->story_section_enabled,
            'remove_watermark' => $this->remove_watermark,
        ];

        return $featureMap[$feature] ?? false;
    }

    /**
     * Get all enabled features as array.
     */
    public function getEnabledFeatures(): array
    {
        $features = [];

        if ($this->rsvp_enabled) $features[] = 'rsvp';
        if ($this->gift_enabled) $features[] = 'gift';
        if ($this->qr_checkin_enabled) $features[] = 'qr_checkin';
        if ($this->analytics_enabled) $features[] = 'analytics';
        if ($this->custom_music_enabled) $features[] = 'custom_music';
        if ($this->custom_domain_enabled) $features[] = 'custom_domain';
        if ($this->export_enabled) $features[] = 'export';
        if ($this->whatsapp_blast_enabled) $features[] = 'whatsapp_blast';
        if ($this->guest_book_enabled) $features[] = 'guest_book';
        if ($this->countdown_enabled) $features[] = 'countdown';
        if ($this->story_section_enabled) $features[] = 'story_section';
        if ($this->remove_watermark) $features[] = 'remove_watermark';

        return $features;
    }

    /**
     * Get comparison data for pricing table.
     */
    public function getComparisonData(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->formatted_price,
            'original_price' => $this->formatted_original_price,
            'discount' => $this->discount_percentage,
            'duration' => $this->duration_label,
            'badge' => $this->badge,
            'limits' => [
                'invitations' => $this->max_invitations_display,
                'guests' => $this->max_guests_display,
                'events' => $this->max_events_per_invitation,
                'gift_accounts' => $this->max_gift_accounts,
                'gallery_images' => $this->max_gallery_images,
            ],
            'features' => $this->getEnabledFeatures(),
            'support' => [
                'level' => $this->support_level->shortLabel(),
                'response_hours' => $this->support_response_hours,
            ],
            'features_list' => $this->features_list ?? [],
        ];
    }

    /**
     * Get route key for model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
