<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Package support level enumeration.
 */
enum SupportLevel: string
{
    case Community = 'community';
    case Email = 'email';
    case Priority = 'priority';
    case Dedicated = 'dedicated';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Community => 'Community Support',
            self::Email => 'Email Support',
            self::Priority => 'Priority Support',
            self::Dedicated => 'Dedicated Support',
        };
    }

    /**
     * Get short label.
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::Community => 'Community',
            self::Email => 'Email',
            self::Priority => 'Priority',
            self::Dedicated => 'Dedicated',
        };
    }

    /**
     * Get description.
     */
    public function description(): string
    {
        return match ($this) {
            self::Community => 'Access to community forum and documentation',
            self::Email => 'Email support within 48 hours',
            self::Priority => 'Priority email support within 24 hours',
            self::Dedicated => 'Dedicated support manager with 4-hour response',
        };
    }

    /**
     * Get badge color.
     */
    public function color(): string
    {
        return match ($this) {
            self::Community => 'gray',
            self::Email => 'blue',
            self::Priority => 'yellow',
            self::Dedicated => 'gold',
        };
    }

    /**
     * Get icon.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Community => 'users',
            self::Email => 'envelope',
            self::Priority => 'bolt',
            self::Dedicated => 'star',
        };
    }

    /**
     * Get all options.
     */
    public static function options(): array
    {
        return [
            self::Community->value => self::Community->label(),
            self::Email->value => self::Email->label(),
            self::Priority->value => self::Priority->label(),
            self::Dedicated->value => self::Dedicated->label(),
        ];
    }
}
