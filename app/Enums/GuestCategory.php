<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Guest categorization for organization and filtering.
 */
enum GuestCategory: string
{
    case Family = 'family';
    case Friend = 'friend';
    case Vip = 'vip';
    case Colleague = 'colleague';
    case Neighbor = 'neighbor';
    case Other = 'other';

    /**
     * Get human-readable label for the category.
     */
    public function label(): string
    {
        return match ($this) {
            self::Family => 'Family',
            self::Friend => 'Friend',
            self::Vip => 'VIP',
            self::Colleague => 'Colleague',
            self::Neighbor => 'Neighbor',
            self::Other => 'Other',
        };
    }

    /**
     * Get icon name for the category.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Family => 'home',
            self::Friend => 'users',
            self::Vip => 'star',
            self::Colleague => 'briefcase',
            self::Neighbor => 'map-pin',
            self::Other => 'user',
        };
    }

    /**
     * Get CSS color class for category badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::Family => 'rose',
            self::Friend => 'blue',
            self::Vip => 'gold',
            self::Colleague => 'purple',
            self::Neighbor => 'green',
            self::Other => 'gray',
        };
    }

    /**
     * Get priority order for sorting (lower = higher priority).
     */
    public function priority(): int
    {
        return match ($this) {
            self::Vip => 1,
            self::Family => 2,
            self::Friend => 3,
            self::Colleague => 4,
            self::Neighbor => 5,
            self::Other => 6,
        };
    }

    /**
     * Get all categories as options for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $category) => [$category->value => $category->label()])
            ->toArray();
    }

    /**
     * Get categories sorted by priority.
     *
     * @return array<self>
     */
    public static function sortedByPriority(): array
    {
        $cases = self::cases();
        usort($cases, fn (self $a, self $b) => $a->priority() <=> $b->priority());

        return $cases;
    }
}
