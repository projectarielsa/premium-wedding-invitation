<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'google_id', 'avatar', 'provider', 'email_verified_at'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function emailOtps(): HasMany { return $this->hasMany(EmailOtp::class); }
    public function hasVerifiedEmail(): bool { return $this->email_verified_at !== null; }
    public function markEmailAsVerified(): bool { return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->save(); }
    public function isSocialUser(): bool { return $this->provider !== null; }
    public function isGoogleUser(): bool { return $this->provider === 'google' && $this->google_id !== null; }
    public function getLatestValidOtp(): ?EmailOtp { return $this->emailOtps()->valid()->latest()->first(); }
    public function needsOtpVerification(): bool { return !$this->hasVerifiedEmail() && !$this->isSocialUser(); }
    public function getAvatarUrl(): string { return $this->avatar ?: "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=d4af37&color=fff&size=128"; }
    public function getInitials(): string { $words = explode(' ', $this->name); $initials = ''; foreach ($words as $word) { if (!empty($word)) $initials .= mb_strtoupper(mb_substr($word, 0, 1)); } return mb_substr($initials, 0, 2); }
}
