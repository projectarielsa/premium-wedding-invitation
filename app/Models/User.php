<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'provider',
        'email_verified_at',
        'role',
        'active_package_id',
        'package_started_at',
        'package_expires_at',
        'is_suspended',
        'suspension_reason',
        'phone_number',
        'whatsapp',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'package_started_at' => 'datetime',
            'package_expires_at' => 'datetime',
            'is_suspended' => 'boolean',
        ];
    }

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'customer',
        'is_suspended' => false,
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function emailOtps(): HasMany
    {
        return $this->hasMany(EmailOtp::class);
    }

    /**
     * Get the invitations owned by this user.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the active package for this user.
     */
    public function activePackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'active_package_id');
    }

    /**
     * Get all orders for this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // =========================================================================
    // EMAIL & AUTH METHODS
    // =========================================================================

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->save();
    }

    public function isSocialUser(): bool
    {
        return $this->provider !== null;
    }

    public function isGoogleUser(): bool
    {
        return $this->provider === 'google' && $this->google_id !== null;
    }

    public function getLatestValidOtp(): ?EmailOtp
    {
        return $this->emailOtps()->valid()->latest()->first();
    }

    public function needsOtpVerification(): bool
    {
        return !$this->hasVerifiedEmail() && !$this->isSocialUser();
    }

    // =========================================================================
    // ROLE METHODS
    // =========================================================================

    /**
     * Check if user is an admin (admin or super_admin).
     */
    public function isAdmin(): bool
    {
        return $this->role?->isAdmin() ?? false;
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role?->isSuperAdmin() ?? false;
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    /**
     * Check if user has specific role.
     */
    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->is_suspended;
    }

    // =========================================================================
    // PACKAGE METHODS
    // =========================================================================

    /**
     * Check if user has an active package.
     */
    public function hasActivePackage(): bool
    {
        if (!$this->active_package_id) {
            return false;
        }

        // Check if package has expired
        if ($this->package_expires_at && $this->package_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get the effective package (active or default basic).
     */
    public function getEffectivePackage(): ?Package
    {
        if ($this->hasActivePackage()) {
            return $this->activePackage;
        }

        // Return basic package as fallback
        return Package::where('slug', 'basic')->first();
    }

    /**
     * Check if user's package is expired.
     */
    public function isPackageExpired(): bool
    {
        if (!$this->package_expires_at) {
            return false;
        }

        return $this->package_expires_at->isPast();
    }

    /**
     * Get days until package expires.
     */
    public function getDaysUntilPackageExpiresAttribute(): ?int
    {
        if (!$this->package_expires_at) {
            return null;
        }

        if ($this->package_expires_at->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->package_expires_at);
    }

    /**
     * Check if package is expiring soon (within 30 days).
     */
    public function isPackageExpiringSoon(): bool
    {
        $days = $this->days_until_package_expires;
        return $days !== null && $days > 0 && $days <= 30;
    }

    /**
     * Activate a package for the user.
     */
    public function activatePackage(Package $package, ?\DateTimeInterface $startsAt = null): bool
    {
        $startsAt = $startsAt ?? now();
        $expiresAt = $startsAt->copy()->addDays($package->duration_days);

        return $this->update([
            'active_package_id' => $package->id,
            'package_started_at' => $startsAt,
            'package_expires_at' => $expiresAt,
        ]);
    }

    /**
     * Deactivate/remove user's package.
     */
    public function deactivatePackage(): bool
    {
        return $this->update([
            'active_package_id' => null,
            'package_started_at' => null,
            'package_expires_at' => null,
        ]);
    }

    /**
     * Check if user can access a feature based on their package.
     */
    public function canAccessFeature(string $feature): bool
    {
        $package = $this->getEffectivePackage();

        if (!$package) {
            return false;
        }

        return $package->hasFeature($feature);
    }

    /**
     * Check if user can access a template based on their package.
     */
    public function canAccessTemplate(Template $template): bool
    {
        $package = $this->getEffectivePackage();

        if (!$package) {
            return !$template->is_premium;
        }

        return $package->canAccessTemplate($template);
    }

    /**
     * Get the package name display.
     */
    public function getPackageNameAttribute(): string
    {
        if ($this->hasActivePackage() && $this->activePackage) {
            return $this->activePackage->name;
        }

        return 'Basic';
    }

    // =========================================================================
    // PROFILE & DISPLAY METHODS
    // =========================================================================

    public function getAvatarUrl(): string
    {
        return $this->avatar ?: "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=d4af37&color=fff&size=128";
    }

    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
        }
        return mb_substr($initials, 0, 2);
    }

    /**
     * Get formatted WhatsApp number.
     */
    public function getFormattedWhatsappAttribute(): ?string
    {
        $number = $this->whatsapp ?? $this->phone_number;

        if (!$number) {
            return null;
        }

        // Remove non-numeric
        $number = preg_replace('/[^0-9]/', '', $number);

        // Add Indonesia code if starts with 0
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    // =========================================================================
    // STATISTICS
    // =========================================================================

    /**
     * Get user statistics.
     */
    public function getStats(): array
    {
        return [
            'total_invitations' => $this->invitations()->count(),
            'published_invitations' => $this->invitations()->published()->count(),
            'total_guests' => $this->invitations()
                ->withCount('guests')
                ->get()
                ->sum('guests_count'),
            'total_rsvps' => $this->invitations()
                ->withCount('rsvps')
                ->get()
                ->sum('rsvps_count'),
        ];
    }
}
