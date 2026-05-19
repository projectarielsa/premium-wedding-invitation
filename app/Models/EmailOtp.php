<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'code', 'expires_at', 'verified_at', 'attempt_count', 'resend_available_at', 'ip_address', 'user_agent'];
    protected $casts = ['expires_at' => 'datetime', 'verified_at' => 'datetime', 'resend_available_at' => 'datetime', 'attempt_count' => 'integer'];
    protected $hidden = ['code'];

    public const MAX_ATTEMPTS = 5;
    public const EXPIRATION_MINUTES = 10;
    public const RESEND_COOLDOWN_SECONDS = 60;

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function isExpired(): bool { return $this->expires_at->isPast(); }
    public function isVerified(): bool { return $this->verified_at !== null; }
    public function hasExceededMaxAttempts(): bool { return $this->attempt_count >= self::MAX_ATTEMPTS; }
    public function canResend(): bool { return $this->resend_available_at === null || $this->resend_available_at->isPast(); }
    public function getResendCooldownRemaining(): int { return $this->canResend() ? 0 : (int) now()->diffInSeconds($this->resend_available_at, false); }
    public function getRemainingAttempts(): int { return max(0, self::MAX_ATTEMPTS - $this->attempt_count); }
    public function incrementAttempts(): void { $this->increment('attempt_count'); }
    public function markAsVerified(): void { $this->update(['verified_at' => now()]); }
    public function scopeValid($query) { return $query->whereNull('verified_at')->where('expires_at', '>', now())->where('attempt_count', '<', self::MAX_ATTEMPTS); }
    public function scopeForUser($query, int $userId) { return $query->where('user_id', $userId); }
    public function scopeLatest($query) { return $query->orderBy('created_at', 'desc'); }
}
