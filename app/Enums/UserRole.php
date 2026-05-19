<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * User role enumeration.
 */
enum UserRole: string
{
    case Customer = 'customer';
    case Admin = 'admin';
    case SuperAdmin = 'super_admin';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Customer',
            self::Admin => 'Admin',
            self::SuperAdmin => 'Super Admin',
        };
    }

    /**
     * Get badge color.
     */
    public function color(): string
    {
        return match ($this) {
            self::Customer => 'blue',
            self::Admin => 'yellow',
            self::SuperAdmin => 'red',
        };
    }

    /**
     * Get icon name.
     */
    public function icon(): string
    {
        return match ($this) {
            self::Customer => 'user',
            self::Admin => 'shield-check',
            self::SuperAdmin => 'key',
        };
    }

    /**
     * Check if role has admin privileges.
     */
    public function isAdmin(): bool
    {
        return in_array($this, [self::Admin, self::SuperAdmin]);
    }

    /**
     * Check if role is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this === self::SuperAdmin;
    }

    /**
     * Get all roles as options.
     */
    public static function options(): array
    {
        return [
            self::Customer->value => self::Customer->label(),
            self::Admin->value => self::Admin->label(),
            self::SuperAdmin->value => self::SuperAdmin->label(),
        ];
    }

    /**
     * Get admin roles.
     */
    public static function adminRoles(): array
    {
        return [self::Admin, self::SuperAdmin];
    }
}
