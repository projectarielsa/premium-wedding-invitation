<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvitationStatus;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wedding invitation model.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $template_id
 * @property string $title
 * @property string $slug
 * @property string $bride_name
 * @property string $groom_name
 * @property string|null $bride_parent
 * @property string|null $groom_parent
 * @property string|null $opening_message
 * @property array|null $story_section
 * @property string|null $cover_image
 * @property array|null $gallery
 * @property string|null $music_url
 * @property \Illuminate\Support\Carbon|null $event_date
 * @property string|null $location
 * @property string|null $google_maps_url
 * @property string|null $dress_code
 * @property array|null $theme_settings
 * @property array|null $custom_css
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_image
 * @property array|null $settings
 * @property InvitationStatus $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $view_count
 * @property int $unique_visitor_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read Template|null $template
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Event> $events
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Guest> $guests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Rsvp> $rsvps
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GiftAccount> $giftAccounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InvitationAnalytic> $analytics
 */
class Invitation extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    /**
     * Slug source field(s).
     *
     * @var array<string>
     */
    protected array $slugSource = ['bride_name', 'groom_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'slug',
        'bride_name',
        'groom_name',
        'bride_parent',
        'groom_parent',
        'opening_message',
        'story_section',
        'cover_image',
        'gallery',
        'music_url',
        'event_date',
        'location',
        'google_maps_url',
        'dress_code',
        'theme_settings',
        'custom_css',
        'seo_title',
        'seo_description',
        'seo_image',
        'settings',
        'status',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'story_section' => 'array',
        'gallery' => 'array',
        'theme_settings' => 'array',
        'custom_css' => 'array',
        'settings' => 'array',
        'status' => InvitationStatus::class,
        'event_date' => 'date',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'unique_visitor_count' => 'integer',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'draft',
        'view_count' => 0,
        'unique_visitor_count' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the owner of this invitation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template used by this invitation.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the events for this invitation.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->ordered();
    }

    /**
     * Get the guests for this invitation.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the RSVPs for this invitation.
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    /**
     * Get the gift accounts for this invitation.
     */
    public function giftAccounts(): HasMany
    {
        return $this->hasMany(GiftAccount::class)->ordered();
    }

    /**
     * Get the analytics for this invitation.
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(InvitationAnalytic::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to filter by status.
     */
    public function scopeStatus(Builder $query, InvitationStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to only draft invitations.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', InvitationStatus::Draft);
    }

    /**
     * Scope to only published invitations.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', InvitationStatus::Published);
    }

    /**
     * Scope to only archived invitations.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', InvitationStatus::Archived);
    }

    /**
     * Scope to filter by upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    /**
     * Scope to filter by past events.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    /**
     * Scope to order by most recent.
     */
    public function scopeLatest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderByDesc($column);
    }

    /**
     * Scope to order by most popular.
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('view_count');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the couple's display name.
     */
    public function getCoupleNameAttribute(): string
    {
        return $this->bride_name . ' & ' . $this->groom_name;
    }

    /**
     * Get the public URL for this invitation.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('invitation.public', $this->slug);
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }

        return asset('storage/' . $this->cover_image);
    }

    /**
     * Get SEO title with fallback.
     */
    public function getSeoTitleDisplayAttribute(): string
    {
        return $this->seo_title ?? "Wedding Invitation - {$this->couple_name}";
    }

    /**
     * Get SEO description with fallback.
     */
    public function getSeoDescriptionDisplayAttribute(): string
    {
        return $this->seo_description
            ?? "You are cordially invited to the wedding of {$this->couple_name}";
    }

    /**
     * Check if RSVP is enabled.
     */
    public function getRsvpEnabledAttribute(): bool
    {
        return $this->settingBool('rsvp_enabled', true);
    }

    /**
     * Check if gift section is enabled.
     */
    public function getGiftEnabledAttribute(): bool
    {
        return $this->settingBool('gift_enabled', true);
    }

    /**
     * Check if guest book is enabled.
     */
    public function getGuestBookEnabledAttribute(): bool
    {
        return $this->settingBool('guest_book_enabled', true);
    }

    /**
     * Check if countdown is enabled.
     */
    public function getCountdownEnabledAttribute(): bool
    {
        return $this->settingBool('countdown_enabled', true);
    }

    /**
     * Check if music autoplay is enabled.
     */
    public function getMusicAutoplayAttribute(): bool
    {
        return $this->settingBool('music_autoplay', false);
    }

    /**
     * Safely cast a settings value to boolean using filter_var.
     * Handles: true, false, 1, 0, "1", "0", "true", "false", null
     */
    private function settingBool(string $key, bool $default = false): bool
    {
        return filter_var(data_get($this->settings, $key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get max attendance per guest.
     */
    public function getMaxAttendancePerGuestAttribute(): int
    {
        return $this->settings['max_attendance_per_guest'] ?? 5;
    }

    /**
     * Get days until event.
     */
    public function getDaysUntilEventAttribute(): ?int
    {
        if (! $this->event_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->event_date, false);
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Check if invitation is publicly viewable.
     */
    public function isPublic(): bool
    {
        return $this->status === InvitationStatus::Published;
    }

    /**
     * Check if invitation can be edited.
     */
    public function isEditable(): bool
    {
        return $this->status !== InvitationStatus::Archived;
    }

    /**
     * Publish the invitation.
     */
    public function publish(): bool
    {
        if ($this->status === InvitationStatus::Archived) {
            return false;
        }

        return $this->update([
            'status' => InvitationStatus::Published,
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    /**
     * Unpublish (set to draft) the invitation.
     */
    public function unpublish(): bool
    {
        return $this->update([
            'status' => InvitationStatus::Draft,
        ]);
    }

    /**
     * Archive the invitation.
     */
    public function archive(): bool
    {
        return $this->update([
            'status' => InvitationStatus::Archived,
        ]);
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Increment unique visitor count.
     */
    public function incrementUniqueVisitorCount(): void
    {
        $this->increment('unique_visitor_count');
    }

    /**
     * Get merged theme settings (template defaults + custom overrides).
     */
    public function getMergedThemeSettings(): array
    {
        $templateDefaults = $this->template?->getDefaultThemeConfig() ?? [];
        $customSettings = $this->theme_settings ?? [];

        return array_merge($templateDefaults, $customSettings);
    }

    /**
     * Get the primary event (first event by date).
     */
    public function getPrimaryEvent(): ?Event
    {
        return $this->events()->orderBy('event_date')->first();
    }

    /**
     * Get RSVP statistics.
     */
    public function getRsvpStats(): array
    {
        $stats = $this->rsvps()
            ->selectRaw('attendance_status, COUNT(*) as count, SUM(attendance_count) as total_guests')
            ->groupBy('attendance_status')
            ->get()
            ->keyBy('attendance_status');

        return [
            'total_invited' => $this->guests()->count(),
            'total_responded' => $this->rsvps()->whereNotNull('responded_at')->count(),
            'attending' => $stats->get('attending')?->count ?? 0,
            'attending_guests' => $stats->get('attending')?->total_guests ?? 0,
            'not_attending' => $stats->get('not_attending')?->count ?? 0,
            'maybe' => $stats->get('maybe')?->count ?? 0,
            'pending' => $stats->get('pending')?->count ?? 0,
        ];
    }

    /**
     * Duplicate this invitation.
     */
    public function duplicate(): static
    {
        $clone = $this->replicate([
            'slug',
            'status',
            'published_at',
            'view_count',
            'unique_visitor_count',
        ]);

        $clone->title = $this->title . ' (Copy)';
        $clone->status = InvitationStatus::Draft;
        $clone->save();

        // Duplicate events
        foreach ($this->events as $event) {
            $eventClone = $event->replicate();
            $eventClone->invitation_id = $clone->id;
            $eventClone->save();
        }

        // Duplicate gift accounts
        foreach ($this->giftAccounts as $giftAccount) {
            $giftAccountClone = $giftAccount->replicate(['view_count', 'copy_count']);
            $giftAccountClone->invitation_id = $clone->id;
            $giftAccountClone->save();
        }

        return $clone;
    }

    /**
     * Get route key for model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
