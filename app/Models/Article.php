<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Article model for SEO blog content.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $thumbnail
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string $category
 * @property array|null $tags
 * @property int|null $author_id
 * @property bool $is_featured
 * @property bool $is_published
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $author
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'meta_title',
        'meta_description',
        'category',
        'tags',
        'author_id',
        'is_featured',
        'is_published',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'view_count' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'category' => 'tips',
        'is_featured' => false,
        'is_published' => false,
        'view_count' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the author of the article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only published articles.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to only featured articles.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order by latest.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    /**
     * Scope to order by popularity.
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('view_count');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the SEO title.
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?? $this->title;
    }

    /**
     * Get the SEO description.
     */
    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?? $this->excerpt ?? substr(strip_tags($this->content), 0, 160);
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        return asset('storage/' . $this->thumbnail);
    }

    /**
     * Get reading time estimate.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Get formatted published date.
     */
    public function getPublishedDateAttribute(): ?string
    {
        return $this->published_at?->format('d M Y');
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Increment view count.
     */
    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    /**
     * Get available categories.
     */
    public static function getCategories(): array
    {
        return [
            'tips' => 'Tips & Tricks',
            'inspirasi' => 'Inspirasi Pernikahan',
            'tutorial' => 'Tutorial',
            'berita' => 'Berita & Update',
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
