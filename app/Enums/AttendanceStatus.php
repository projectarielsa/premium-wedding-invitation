<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * RSVP attendance status for guests.
 */
enum AttendanceStatus: string
{
    case Pending = 'pending';
    case Attending = 'attending';
    case NotAttending = 'not_attending';
    case Maybe = 'maybe';

    /**
     * Get human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Attending => 'Attending',
            self::NotAttending => 'Not Attending',
            self::Maybe => 'Maybe',
        };
    }

    /**
     * Get short label for compact displays.
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Attending => 'Yes',
            self::NotAttending => 'No',
            self::Maybe => 'Maybe',
        };
    }

    /**
     * Get icon name for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Pending => 'clock',
            self::Attending => 'check-circle',
            self::NotAttending => 'x-circle',
            self::Maybe => 'question-mark-circle',
        };
    }

    /**
     * Get CSS color class for status badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Attending => 'green',
            self::NotAttending => 'red',
            self::Maybe => 'yellow',
        };
    }

    /**
     * Check if this status counts toward confirmed attendance.
     */
    public function isConfirmed(): bool
    {
        return $this === self::Attending;
    }

    /**
     * Check if this status is a definite response (not pending).
     */
    public function hasResponded(): bool
    {
        return $this !== self::Pending;
    }

    /**
     * Get all statuses as options for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->toArray();
    }

    /**
     * Get response options for RSVP form (excludes Pending).
     *
     * @return array<string, string>
     */
    public static function responseOptions(): array
    {
        return collect(self::cases())
            ->filter(fn (self $status) => $status !== self::Pending)
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->toArray();
    }
}
