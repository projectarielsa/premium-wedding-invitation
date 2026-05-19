<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Digital gift account types for wedding gifts.
 */
enum GiftAccountType: string
{
    case BankTransfer = 'bank_transfer';
    case EWallet = 'e_wallet';
    case Qris = 'qris';

    /**
     * Get human-readable label for the account type.
     */
    public function label(): string
    {
        return match ($this) {
            self::BankTransfer => 'Bank Transfer',
            self::EWallet => 'E-Wallet',
            self::Qris => 'QRIS',
        };
    }

    /**
     * Get description for the account type.
     */
    public function description(): string
    {
        return match ($this) {
            self::BankTransfer => 'Transfer via bank account',
            self::EWallet => 'Send via e-wallet apps',
            self::Qris => 'Scan QR code to pay',
        };
    }

    /**
     * Get icon name for the account type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::BankTransfer => 'building-library',
            self::EWallet => 'device-phone-mobile',
            self::Qris => 'qr-code',
        };
    }

    /**
     * Check if this type requires account number.
     */
    public function requiresAccountNumber(): bool
    {
        return in_array($this, [self::BankTransfer, self::EWallet]);
    }

    /**
     * Check if this type supports QR image.
     */
    public function supportsQrImage(): bool
    {
        return $this === self::Qris;
    }

    /**
     * Get common providers for this account type.
     *
     * @return array<string>
     */
    public function providers(): array
    {
        return match ($this) {
            self::BankTransfer => [
                'BCA',
                'Mandiri',
                'BNI',
                'BRI',
                'CIMB Niaga',
                'Bank Jago',
                'Bank Jenius',
                'Permata',
                'OCBC NISP',
                'Danamon',
                'Other',
            ],
            self::EWallet => [
                'GoPay',
                'OVO',
                'DANA',
                'ShopeePay',
                'LinkAja',
                'Other',
            ],
            self::Qris => [
                'QRIS',
            ],
        };
    }

    /**
     * Get all account types as options for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
