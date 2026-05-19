<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Payment status enumeration.
 */
enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Pending = 'pending';
    case Verified = 'verified';
    case Failed = 'failed';
    case Refunded = 'refunded';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Belum Bayar',
            self::Pending => 'Menunggu Verifikasi',
            self::Verified => 'Terverifikasi',
            self::Failed => 'Gagal',
            self::Refunded => 'Dikembalikan',
        };
    }

    /**
     * Get badge color.
     */
    public function color(): string
    {
        return match ($this) {
            self::Unpaid => 'gray',
            self::Pending => 'yellow',
            self::Verified => 'green',
            self::Failed => 'red',
            self::Refunded => 'blue',
        };
    }

    /**
     * Get Tailwind badge classes.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-gray-100 text-gray-800',
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Verified => 'bg-green-100 text-green-800',
            self::Failed => 'bg-red-100 text-red-800',
            self::Refunded => 'bg-blue-100 text-blue-800',
        };
    }

    /**
     * Get icon name.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Unpaid => 'clock',
            self::Pending => 'eye',
            self::Verified => 'check-circle',
            self::Failed => 'x-circle',
            self::Refunded => 'arrow-uturn-left',
        };
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this === self::Verified;
    }

    /**
     * Check if payment needs verification.
     */
    public function needsVerification(): bool
    {
        return $this === self::Pending;
    }

    /**
     * Get all options.
     */
    public static function options(): array
    {
        return [
            self::Unpaid->value => self::Unpaid->label(),
            self::Pending->value => self::Pending->label(),
            self::Verified->value => self::Verified->label(),
            self::Failed->value => self::Failed->label(),
            self::Refunded->value => self::Refunded->label(),
        ];
    }
}
