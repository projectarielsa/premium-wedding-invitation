<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait for automatic slug generation.
 *
 * Provides SEO-friendly URL slugs with uniqueness handling.
 *
 * @mixin Model
 */
trait HasSlug
{
    /**
     * Boot the trait.
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model): void {
            if (empty($model->{$model->getSlugColumn()})) {
                $model->{$model->getSlugColumn()} = $model->generateUniqueSlug();
            }
        });

        static::updating(function (Model $model): void {
            // Regenerate slug if source field changed and slug wasn't manually set
            if ($model->shouldRegenerateSlug()) {
                $model->{$model->getSlugColumn()} = $model->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique slug based on source field(s).
     */
    public function generateUniqueSlug(): string
    {
        $slug = $this->createSlugFromSource();
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Create base slug from source field(s).
     */
    protected function createSlugFromSource(): string
    {
        $sources = $this->getSlugSource();

        if (is_array($sources)) {
            $parts = array_map(fn ($source) => $this->{$source} ?? '', $sources);
            $value = implode(' ', array_filter($parts));
        } else {
            $value = $this->{$sources} ?? '';
        }

        // Handle empty value
        if (empty(trim($value))) {
            $value = 'invitation-' . Str::random(8);
        }

        return Str::slug($value, '-');
    }

    /**
     * Check if slug already exists in database.
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::withoutGlobalScopes()
            ->where($this->getSlugColumn(), $slug);

        // Exclude current model when updating
        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        // Scope to user if applicable (multi-tenant)
        if ($this->shouldScopeSlugToUser() && isset($this->user_id)) {
            $query->where('user_id', $this->user_id);
        }

        return $query->exists();
    }

    /**
     * Determine if slug should be regenerated on update.
     */
    protected function shouldRegenerateSlug(): bool
    {
        $sources = $this->getSlugSource();
        $sources = is_array($sources) ? $sources : [$sources];

        foreach ($sources as $source) {
            if ($this->isDirty($source)) {
                // Only regenerate if slug wasn't manually changed
                return ! $this->isDirty($this->getSlugColumn());
            }
        }

        return false;
    }

    /**
     * Get the column name for the slug.
     */
    public function getSlugColumn(): string
    {
        return $this->slugColumn ?? 'slug';
    }

    /**
     * Get the source field(s) for generating slug.
     * Can be a string or array of strings.
     *
     * @return string|array<string>
     */
    public function getSlugSource(): string|array
    {
        return $this->slugSource ?? 'title';
    }

    /**
     * Whether slug should be unique per user (multi-tenant).
     */
    public function shouldScopeSlugToUser(): bool
    {
        return $this->scopeSlugToUser ?? false;
    }

    /**
     * Get the route key for Laravel's implicit binding.
     */
    public function getRouteKeyName(): string
    {
        return $this->getSlugColumn();
    }

    /**
     * Manually set a custom slug.
     */
    public function setCustomSlug(string $slug): static
    {
        $this->{$this->getSlugColumn()} = Str::slug($slug);

        return $this;
    }
}
