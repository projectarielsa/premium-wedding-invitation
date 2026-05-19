<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Invitation daily analytics model.
 *
 * @property int $id
 * @property int $invitation_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $page_views
 * @property int $unique_visitors
 * @property int $rsvp_submissions
 * @property int $gift_section_views
 * @property int $gift_copy_clicks
 * @property int $gallery_views
 * @property int $map_clicks
 * @property int $whatsapp_shares
 * @property int $link_copies
 * @property int $guest_opens
 * @property int $anonymous_opens
 * @property array|null $device_stats
 * @property array|null $referral_stats
 * @property array|null $browser_stats
 * @property array|null $location_stats
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Invitation $invitation
 */
class InvitationAnalytic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invitation_analytics';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invitation_id',
        'date',
        'page_views',
        'unique_visitors',
        'rsvp_submissions',
        'gift_section_views',
        'gift_copy_clicks',
        'gallery_views',
        'map_clicks',
        'whatsapp_shares',
        'link_copies',
        'guest_opens',
        'anonymous_opens',
        'device_stats',
        'referral_stats',
        'browser_stats',
        'location_stats',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'page_views' => 'integer',
        'unique_visitors' => 'integer',
        'rsvp_submissions' => 'integer',
        'gift_section_views' => 'integer',
        'gift_copy_clicks' => 'integer',
        'gallery_views' => 'integer',
        'map_clicks' => 'integer',
        'whatsapp_shares' => 'integer',
        'link_copies' => 'integer',
        'guest_opens' => 'integer',
        'anonymous_opens' => 'integer',
        'device_stats' => 'array',
        'referral_stats' => 'array',
        'browser_stats' => 'array',
        'location_stats' => 'array',
    ];



    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'page_views' => 0,
        'unique_visitors' => 0,
        'rsvp_submissions' => 0,
        'gift_section_views' => 0,
        'gift_copy_clicks' => 0,
        'gallery_views' => 0,
        'map_clicks' => 0,
        'whatsapp_shares' => 0,
        'link_copies' => 0,
        'guest_opens' => 0,
        'anonymous_opens' => 0,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the invitation this analytic belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
    }

    /**
     * Scope to filter today's analytics.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->where('date', now()->toDateString());
    }



    /**
     * Scope to filter last 7 days.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->dateRange(now()->subDays(6), now());
    }

    /**
     * Scope to filter last 30 days.
     */
    public function scopeLastMonth(Builder $query): Builder
    {
        return $query->dateRange(now()->subDays(29), now());
    }

    /**
     * Scope to order by date.
     */
    public function scopeOrderByDate(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('date', $direction);
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Get or create today's analytic record for an invitation.
     */
    public static function getOrCreateForToday(int $invitationId): static
    {
        return static::firstOrCreate([
            'invitation_id' => $invitationId,
            'date' => now()->toDateString(),
        ]);
    }

    /**
     * Increment a specific metric.
     */
    public function incrementMetric(string $metric, int $amount = 1): void
    {
        if (in_array($metric, $this->fillable)) {
            $this->increment($metric, $amount);
        }
    }



    /**
     * Record a page view.
     */
    public function recordPageView(bool $isUnique = false, bool $isGuest = false): void
    {
        $this->increment('page_views');

        if ($isUnique) {
            $this->increment('unique_visitors');
        }

        if ($isGuest) {
            $this->increment('guest_opens');
        } else {
            $this->increment('anonymous_opens');
        }
    }

    /**
     * Record device type.
     */
    public function recordDevice(string $deviceType): void
    {
        $stats = $this->device_stats ?? [];
        $stats[$deviceType] = ($stats[$deviceType] ?? 0) + 1;
        $this->update(['device_stats' => $stats]);
    }

    /**
     * Record referral source.
     */
    public function recordReferral(string $source): void
    {
        $stats = $this->referral_stats ?? [];
        $stats[$source] = ($stats[$source] ?? 0) + 1;
        $this->update(['referral_stats' => $stats]);
    }

    /**
     * Get total engagement score.
     */
    public function getEngagementScore(): int
    {
        return $this->page_views +
            ($this->rsvp_submissions * 10) +
            ($this->gift_copy_clicks * 5) +
            ($this->gallery_views * 2) +
            ($this->map_clicks * 3) +
            ($this->whatsapp_shares * 8);
    }

    /**
     * Get summary statistics.
     */
    public function getSummary(): array
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'page_views' => $this->page_views,
            'unique_visitors' => $this->unique_visitors,
            'rsvp_submissions' => $this->rsvp_submissions,
            'engagement_score' => $this->getEngagementScore(),
            'top_device' => $this->getTopFromStats($this->device_stats),
            'top_referral' => $this->getTopFromStats($this->referral_stats),
        ];
    }

    /**
     * Get top item from stats array.
     */
    protected function getTopFromStats(?array $stats): ?string
    {
        if (empty($stats)) {
            return null;
        }

        arsort($stats);

        return array_key_first($stats);
    }
}
