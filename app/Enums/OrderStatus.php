<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Order status enumeration.
 */
enum OrderStatus: string
{
    case Pending = 'pending';
    case WaitingPayment = 'waiting_payment';
    case Paid = 'paid';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::WaitingPayment => 'Menunggu Pembayaran',
            self::Paid => 'Sudah Bayar',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
            self::Cancelled => 'Dibatalkan',
            self::Completed => 'Selesai',
        };
    }

    /**
     * Get English label.
     */
    public function englishLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::WaitingPayment => 'Waiting Payment',
            self::Paid => 'Paid',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }

    /**
     * Get badge color.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::WaitingPayment => 'yellow',
            self::Paid => 'blue',
            self::Approved => 'green',
            self::Rejected => 'red',
            self::Cancelled => 'gray',
            self::Completed => 'emerald',
        };
    }

    /**
     * Get Tailwind badge classes.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-gray-100 text-gray-800',
            self::WaitingPayment => 'bg-yellow-100 text-yellow-800',
            self::Paid => 'bg-blue-100 text-blue-800',
            self::Approved => 'bg-green-100 text-green-800',
            self::Rejected => 'bg-red-100 text-red-800',
            self::Cancelled => 'bg-gray-100 text-gray-600',
            self::Completed => 'bg-emerald-100 text-emerald-800',
        };
    }

    /**
     * Get icon name.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Pending => 'clock',
            self::WaitingPayment => 'credit-card',
            self::Paid => 'banknotes',
            self::Approved => 'check-circle',
            self::Rejected => 'x-circle',
            self::Cancelled => 'x-mark',
            self::Completed => 'check-badge',
        };
    }

    /**
     * Check if order is in a final state.
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::Completed, self::Rejected, self::Cancelled]);
    }

    /**
     * Check if order is awaiting admin action.
     */
    public function isAwaitingAdmin(): bool
    {
        return in_array($this, [self::Paid]);
    }

    /**
     * Check if order is active (not final).
     */
    public function isActive(): bool
    {
        return !$this->isFinal();
    }

    /**
     * Check if payment proof can be uploaded.
     */
    public function canUploadPayment(): bool
    {
        return in_array($this, [self::WaitingPayment, self::Pending]);
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::WaitingPayment]);
    }

    /**
     * Get allowed next statuses.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::WaitingPayment, self::Cancelled],
            self::WaitingPayment => [self::Paid, self::Cancelled],
            self::Paid => [self::Approved, self::Rejected],
            self::Approved => [self::Completed],
            self::Rejected => [],
            self::Cancelled => [],
            self::Completed => [],
        };
    }

    /**
     * Check if can transition to status.
     */
    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions());
    }

    /**
     * Get all options.
     */
    public static function options(): array
    {
        return [
            self::Pending->value => self::Pending->label(),
            self::WaitingPayment->value => self::WaitingPayment->label(),
            self::Paid->value => self::Paid->label(),
            self::Approved->value => self::Approved->label(),
            self::Rejected->value => self::Rejected->label(),
            self::Cancelled->value => self::Cancelled->label(),
            self::Completed->value => self::Completed->label(),
        ];
    }

    /**
     * Get statuses requiring admin attention.
     */
    public static function adminActionRequired(): array
    {
        return [self::Paid];
    }
}
