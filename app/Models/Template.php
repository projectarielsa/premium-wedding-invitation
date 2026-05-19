<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Invitation template model.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $preview_image
 * @property string|null $thumbnail_image
 * @property array|null $theme_config
 * @property array|null $sections
 * @property string $category
 * @property bool $is_premium
 * @property bool $is_active
 * @property int $sort_order
 * @property int $usage_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Invitation> $invitations
 */
class Template extends Model
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
        'preview_image',
        'thumbnail_image',
        'theme_config',
        'sections',
        'category',
        'is_premium',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'theme_config' => 'array',
        'sections' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'usage_count' => 'integer',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_premium' => false,
        'is_active' => true,
        'sort_order' => 0,
        'usage_count' => 0,
        'category' => 'premium',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get invitations using this template.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only active templates.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only premium templates.
     */
    public function scopePremium(Builder $query): Builder
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope to only free templates.
     */
    public function scopeFree(Builder $query): Builder
    {
        return $query->where('is_premium', false);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order by popularity.
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('usage_count');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the preview image URL.
     */
    public function getPreviewImageUrlAttribute(): ?string
    {
        if (! $this->preview_image) {
            return null;
        }

        if (str_starts_with($this->preview_image, 'http')) {
            return $this->preview_image;
        }

        return asset('storage/' . $this->preview_image);
    }

    /**
     * Get the thumbnail image URL.
     */
    public function getThumbnailImageUrlAttribute(): ?string
    {
        if (! $this->thumbnail_image) {
            return $this->preview_image_url;
        }

        if (str_starts_with($this->thumbnail_image, 'http')) {
            return $this->thumbnail_image;
        }

        return asset('storage/' . $this->thumbnail_image);
    }

    /**
     * Get the view path for this template.
     */
    public function getViewPathAttribute(): string
    {
        return 'templates.' . $this->slug . '.index';
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get default theme configuration.
     */
    public function getDefaultThemeConfig(): array
    {
        return $this->theme_config ?? [
            'primary_color' => '#d4af37',
            'secondary_color' => '#1a1a1a',
            'background_color' => '#faf8f5',
            'text_color' => '#1a1a1a',
            'accent_color' => '#f43f5e',
            'heading_font' => 'Playfair Display',
            'body_font' => 'Inter',
            'accent_font' => 'Cormorant Garamond',
        ];
    }

    /**
     * Get available sections.
     */
    public function getAvailableSections(): array
    {
        return $this->sections ?? [
            'hero',
            'couple',
            'events',
            'story',
            'gallery',
            'rsvp',
            'gift',
            'footer',
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
