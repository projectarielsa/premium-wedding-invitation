<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Invitation publication status.
 *
 * Controls visibility and editing capabilities of invitations.
 */
enum InvitationStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    /**
     * Get human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    /**
     * Get CSS color class for status badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::Draft => 'yellow',
            self::Published => 'green',
            self::Archived => 'gray',
        };
    }

    /**
     * Get icon name for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Draft => 'pencil',
            self::Published => 'check-circle',
            self::Archived => 'archive-box',
        };
    }

    /**
     * Check if invitation is publicly viewable.
     */
    public function isPublic(): bool
    {
        return $this === self::Published;
    }

    /**
     * Check if invitation can be edited.
     */
    public function isEditable(): bool
    {
        return $this !== self::Archived;
    }

    /**
     * Get all statuses as options for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Draft->value => self::Draft->label(),
            self::Published->value => self::Published->label(),
            self::Archived->value => self::Archived->label(),
        ];
    }
}
