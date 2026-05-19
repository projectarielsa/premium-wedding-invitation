<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wedding event model.
 *
 * @property int $id
 * @property int $invitation_id
 * @property EventType $type
 * @property string $name
 * @property \Illuminate\Support\Carbon $event_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string $timezone
 * @property string|null $venue_name
 * @property string|null $venue_address
 * @property string|null $google_maps_url
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $dress_code
 * @property string|null $notes
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Invitation $invitation
 */
class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invitation_id',
        'type',
        'name',
        'event_date',
        'start_time',
        'end_time',
        'timezone',
        'venue_name',
        'venue_address',
        'google_maps_url',
        'latitude',
        'longitude',
        'dress_code',
        'notes',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => EventType::class,
        'event_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'reception',
        'timezone' => 'Asia/Jakarta',
        'sort_order' => 0,
        'is_active' => true,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the invitation this event belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to only active events.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order then date.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('event_date')->orderBy('start_time');
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeOfType(Builder $query, EventType $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    /**
     * Scope to filter past events.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    /**
     * Scope to filter primary events only.
     */
    public function scopePrimary(Builder $query): Builder
    {
        $primaryTypes = array_map(
            fn (EventType $type) => $type->value,
            EventType::primaryTypes()
        );

        return $query->whereIn('type', $primaryTypes);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->translatedFormat('l, j F Y');
    }

    /**
     * Get formatted short date.
     */
    public function getFormattedShortDateAttribute(): string
    {
        return $this->event_date->format('d M Y');
    }

    /**
     * Get formatted time range.
     */
    public function getFormattedTimeAttribute(): ?string
    {
        if (! $this->start_time) {
            return null;
        }

        $start = \Carbon\Carbon::parse($this->start_time)->format('H:i');

        if (! $this->end_time) {
            return $start . ' WIB';
        }

        $end = \Carbon\Carbon::parse($this->end_time)->format('H:i');

        return $start . ' - ' . $end . ' WIB';
    }

    /**
     * Get full venue display.
     */
    public function getFullVenueAttribute(): string
    {
        $parts = array_filter([
            $this->venue_name,
            $this->venue_address,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return $this->type->icon();
    }

    /**
     * Check if event is today.
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->event_date->isToday();
    }

    /**
     * Check if event is in the past.
     */
    public function getIsPastAttribute(): bool
    {
        return $this->event_date->isPast();
    }

    /**
     * Check if event is upcoming.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->event_date->isFuture() || $this->event_date->isToday();
    }

    /**
     * Get days until this event.
     */
    public function getDaysUntilAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->event_date, false);
    }

    /**
     * Get Google Maps embed URL.
     */
    public function getGoogleMapsEmbedUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000!2d{$this->longitude}!3d{$this->latitude}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sid!4v0";
        }

        return null;
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Check if this is a primary event type.
     */
    public function isPrimary(): bool
    {
        return $this->type->isPrimary();
    }

    /**
     * Get Google Calendar URL for this event.
     */
    public function getGoogleCalendarUrl(): string
    {
        $title = urlencode($this->invitation->couple_name . ' - ' . $this->name);
        $details = urlencode($this->notes ?? '');
        $location = urlencode($this->full_venue);

        $startDate = $this->event_date->format('Ymd');
        $endDate = $this->event_date->format('Ymd');

        if ($this->start_time) {
            $startDate .= 'T' . str_replace(':', '', $this->start_time) . '00';
        }

        if ($this->end_time) {
            $endDate .= 'T' . str_replace(':', '', $this->end_time) . '00';
        }

        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$startDate}/{$endDate}&details={$details}&location={$location}";
    }
}
