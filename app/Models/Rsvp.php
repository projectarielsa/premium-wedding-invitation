<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RSVP response model.
 *
 * @property int $id
 * @property int $guest_id
 * @property int $invitation_id
 * @property AttendanceStatus $attendance_status
 * @property int $attendance_count
 * @property string|null $message
 * @property string|null $dietary_requirements
 * @property string|null $special_requests
 * @property \Illuminate\Support\Carbon|null $responded_at
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Guest $guest
 * @property-read Invitation $invitation
 */
class Rsvp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'guest_id',
        'invitation_id',
        'attendance_status',
        'attendance_count',
        'message',
        'dietary_requirements',
        'special_requests',
        'responded_at',
        'ip_address',
        'user_agent',
        'admin_notes',
    ];



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_status' => AttendanceStatus::class,
        'attendance_count' => 'integer',
        'responded_at' => 'datetime',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'attendance_status' => 'pending',
        'attendance_count' => 1,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the guest this RSVP belongs to.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the invitation this RSVP belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to filter by attendance status.
     */
    public function scopeWithStatus(Builder $query, AttendanceStatus $status): Builder
    {
        return $query->where('attendance_status', $status);
    }



    /**
     * Scope to filter attending RSVPs.
     */
    public function scopeAttending(Builder $query): Builder
    {
        return $query->where('attendance_status', AttendanceStatus::Attending);
    }

    /**
     * Scope to filter not attending RSVPs.
     */
    public function scopeNotAttending(Builder $query): Builder
    {
        return $query->where('attendance_status', AttendanceStatus::NotAttending);
    }

    /**
     * Scope to filter maybe RSVPs.
     */
    public function scopeMaybe(Builder $query): Builder
    {
        return $query->where('attendance_status', AttendanceStatus::Maybe);
    }

    /**
     * Scope to filter pending RSVPs.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('attendance_status', AttendanceStatus::Pending);
    }

    /**
     * Scope to filter RSVPs that have responded.
     */
    public function scopeResponded(Builder $query): Builder
    {
        return $query->whereNotNull('responded_at');
    }

    /**
     * Scope to filter RSVPs with messages.
     */
    public function scopeWithMessage(Builder $query): Builder
    {
        return $query->whereNotNull('message')->where('message', '!=', '');
    }

    /**
     * Scope to order by most recent response.
     */
    public function scopeLatestResponse(Builder $query): Builder
    {
        return $query->orderByDesc('responded_at');
    }



    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the attendance status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->attendance_status->label();
    }

    /**
     * Get the attendance status color.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->attendance_status->color();
    }

    /**
     * Get the attendance status icon.
     */
    public function getStatusIconAttribute(): string
    {
        return $this->attendance_status->icon();
    }

    /**
     * Check if confirmed attending.
     */
    public function getIsConfirmedAttribute(): bool
    {
        return $this->attendance_status->isConfirmed();
    }

    /**
     * Check if has responded.
     */
    public function getHasRespondedAttribute(): bool
    {
        return $this->responded_at !== null;
    }

    /**
     * Get formatted response date.
     */
    public function getFormattedRespondedAtAttribute(): ?string
    {
        return $this->responded_at?->translatedFormat('d M Y, H:i');
    }

    // =========================================================================
    // METHODS
    // =========================================================================

    /**
     * Submit RSVP response.
     */
    public function submitResponse(
        AttendanceStatus $status,
        int $attendanceCount = 1,
        ?string $message = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): bool {
        return $this->update([
            'attendance_status' => $status,
            'attendance_count' => $status->isConfirmed() ? $attendanceCount : 0,
            'message' => $message,
            'responded_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Update response.
     */
    public function updateResponse(
        AttendanceStatus $status,
        int $attendanceCount = 1,
        ?string $message = null
    ): bool {
        return $this->update([
            'attendance_status' => $status,
            'attendance_count' => $status->isConfirmed() ? $attendanceCount : 0,
            'message' => $message,
        ]);
    }
}
