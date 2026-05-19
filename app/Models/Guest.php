<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GuestCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Wedding guest model.
 *
 * @property int $id
 * @property int $invitation_id
 * @property string $name
 * @property string|null $phone_number
 * @property string|null $whatsapp
 * @property string|null $email
 * @property GuestCategory $category
 * @property string $slug_token
 * @property string|null $qr_code
 * @property int $max_attendees
 * @property string|null $notes
 * @property int $unique_visit_count
 * @property \Illuminate\Support\Carbon|null $first_visited_at
 * @property \Illuminate\Support\Carbon|null $last_visited_at
 * @property \Illuminate\Support\Carbon|null $whatsapp_sent_at
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property string|null $checked_in_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Invitation $invitation
 * @property-read Rsvp|null $rsvp
 */
class Guest extends Model
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
        'name',
        'phone_number',
        'whatsapp',
        'email',
        'category',
        'slug_token',
        'qr_code',
        'max_attendees',
        'notes',
        'whatsapp_sent_at',
        'checked_in_at',
        'checked_in_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category' => GuestCategory::class,
        'max_attendees' => 'integer',
        'unique_visit_count' => 'integer',
        'first_visited_at' => 'datetime',
        'last_visited_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'category' => 'friend',
        'max_attendees' => 2,
        'unique_visit_count' => 0,
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Guest $guest): void {
            if (empty($guest->slug_token)) {
                $guest->slug_token = (string) Str::uuid();
            }
        });
    }



    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the invitation this guest belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * Get the RSVP for this guest.
     */
    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory(Builder $query, GuestCategory $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter guests who have responded.
     */
    public function scopeResponded(Builder $query): Builder
    {
        return $query->whereHas('rsvp', fn (Builder $q) => $q->whereNotNull('responded_at'));
    }

    /**
     * Scope to filter guests who haven't responded.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereDoesntHave('rsvp')
            ->orWhereHas('rsvp', fn (Builder $q) => $q->whereNull('responded_at'));
    }



    /**
     * Scope to filter guests who have been sent WhatsApp.
     */
    public function scopeWhatsappSent(Builder $query): Builder
    {
        return $query->whereNotNull('whatsapp_sent_at');
    }

    /**
     * Scope to filter guests who haven't been sent WhatsApp.
     */
    public function scopeWhatsappPending(Builder $query): Builder
    {
        return $query->whereNull('whatsapp_sent_at');
    }

    /**
     * Scope to filter checked-in guests.
     */
    public function scopeCheckedIn(Builder $query): Builder
    {
        return $query->whereNotNull('checked_in_at');
    }

    /**
     * Scope to filter guests who have visited.
     */
    public function scopeVisited(Builder $query): Builder
    {
        return $query->where('unique_visit_count', '>', 0);
    }

    /**
     * Scope to order by category priority.
     */
    public function scopeOrderByPriority(Builder $query): Builder
    {
        return $query->orderByRaw("CASE 
            WHEN category = 'vip' THEN 1 
            WHEN category = 'family' THEN 2 
            WHEN category = 'friend' THEN 3 
            WHEN category = 'colleague' THEN 4 
            WHEN category = 'neighbor' THEN 5 
            ELSE 6 END");
    }



    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the personalized invitation URL.
     */
    public function getPersonalizedUrlAttribute(): string
    {
        return route('invitation.public.guest', [
            'slug' => $this->invitation->slug,
            'guest' => $this->slug_token,
        ]);
    }

    /**
     * Get the WhatsApp number to use (fallback to phone_number).
     */
    public function getWhatsappNumberAttribute(): ?string
    {
        return $this->whatsapp ?? $this->phone_number;
    }

    /**
     * Get formatted WhatsApp number (with country code).
     */
    public function getFormattedWhatsappAttribute(): ?string
    {
        $number = $this->whatsapp_number;
        if (! $number) {
            return null;
        }

        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Add Indonesia country code if starts with 0
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // Add + if doesn't have country code
        if (! str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Get WhatsApp share link.
     */
    public function getWhatsappShareLinkAttribute(): ?string
    {
        $number = $this->formatted_whatsapp;
        if (! $number) {
            return null;
        }

        $invitation = $this->invitation;
        $message = urlencode(
            "Kepada Yth. *{$this->name}*\n\n" .
            "Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami:\n\n" .
            "*{$invitation->couple_name}*\n\n" .
            "Untuk informasi lebih lanjut, silakan kunjungi:\n" .
            $this->personalized_url
        );

        return "https://wa.me/{$number}?text={$message}";
    }



    /**
     * Get the category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return $this->category->label();
    }

    /**
     * Get the category color.
     */
    public function getCategoryColorAttribute(): string
    {
        return $this->category->color();
    }

    /**
     * Get the QR code URL.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if (! $this->qr_code) {
            return null;
        }

        if (str_starts_with($this->qr_code, 'http')) {
            return $this->qr_code;
        }

        return asset('storage/' . $this->qr_code);
    }

    /**
     * Check if guest has responded to RSVP.
     */
    public function getHasRespondedAttribute(): bool
    {
        return $this->rsvp?->responded_at !== null;
    }

    /**
     * Check if guest is attending.
     */
    public function getIsAttendingAttribute(): bool
    {
        return $this->rsvp?->attendance_status->value === 'attending';
    }

    /**
     * Check if guest is checked in.
     */
    public function getIsCheckedInAttribute(): bool
    {
        return $this->checked_in_at !== null;
    }



    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Record a visit from this guest.
     */
    public function recordVisit(): void
    {
        $now = now();

        if (! $this->first_visited_at) {
            $this->first_visited_at = $now;
        }

        $this->last_visited_at = $now;
        $this->increment('unique_visit_count');
        $this->save();
    }

    /**
     * Mark WhatsApp as sent.
     */
    public function markWhatsappSent(): void
    {
        $this->update(['whatsapp_sent_at' => now()]);
    }

    /**
     * Check in the guest.
     */
    public function checkIn(?string $staffName = null): void
    {
        $this->update([
            'checked_in_at' => now(),
            'checked_in_by' => $staffName,
        ]);
    }

    /**
     * Undo check in.
     */
    public function undoCheckIn(): void
    {
        $this->update([
            'checked_in_at' => null,
            'checked_in_by' => null,
        ]);
    }

    /**
     * Generate QR code for this guest.
     */
    public function generateQrCode(): string
    {
        // This will be implemented in the GuestService
        // Returns the path to the generated QR code
        return "qr-codes/guests/{$this->slug_token}.png";
    }

    /**
     * Get route key for model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug_token';
    }

    /**
     * Resolve route binding with invitation scope.
     */
    public function resolveRouteBinding($value, $field = null): ?self
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)->first();
    }
}
