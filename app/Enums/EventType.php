<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Wedding event types.
 *
 * Supports Indonesian wedding traditions (Akad Nikah, Resepsi)
 * and international formats.
 */
enum EventType: string
{
    case Akad = 'akad';
    case Reception = 'reception';
    case HolyMatrimony = 'holy_matrimony';
    case TeaPai = 'tea_pai';
    case Ceremony = 'ceremony';
    case Dinner = 'dinner';
    case AfterParty = 'after_party';
    case Other = 'other';

    /**
     * Get human-readable label for the event type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Akad => 'Akad Nikah',
            self::Reception => 'Resepsi',
            self::HolyMatrimony => 'Pemberkatan',
            self::TeaPai => 'Tea Pai Ceremony',
            self::Ceremony => 'Ceremony',
            self::Dinner => 'Wedding Dinner',
            self::AfterParty => 'After Party',
            self::Other => 'Other Event',
        };
    }

    /**
     * Get icon name for the event type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Akad => 'book-open',
            self::Reception => 'sparkles',
            self::HolyMatrimony => 'heart',
            self::TeaPai => 'gift',
            self::Ceremony => 'sun',
            self::Dinner => 'cake',
            self::AfterParty => 'musical-note',
            self::Other => 'calendar',
        };
    }

    /**
     * Get default dress code suggestion for event type.
     */
    public function suggestedDressCode(): ?string
    {
        return match ($this) {
            self::Akad => 'Formal / Traditional',
            self::Reception => 'Formal / Semi-Formal',
            self::HolyMatrimony => 'Formal',
            self::Dinner => 'Smart Casual',
            self::AfterParty => 'Casual',
            default => null,
        };
    }

    /**
     * Check if this is a primary/main event.
     */
    public function isPrimary(): bool
    {
        return in_array($this, [
            self::Akad,
            self::Reception,
            self::HolyMatrimony,
            self::Ceremony,
        ]);
    }

    /**
     * Get all event types as options for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->toArray();
    }

    /**
     * Get primary event types only.
     *
     * @return array<self>
     */
    public static function primaryTypes(): array
    {
        return array_filter(self::cases(), fn (self $type) => $type->isPrimary());
    }
}
