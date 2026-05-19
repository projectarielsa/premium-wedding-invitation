<?php

namespace App\Services\Auth;

use App\Exceptions\OtpException;
use App\Models\EmailOtp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    private const VERIFY_RATE_LIMIT_PREFIX = 'otp-verify:';
    private const RESEND_RATE_LIMIT_PREFIX = 'otp-resend:';
    private const MAX_VERIFY_ATTEMPTS_PER_MINUTE = 5;
    private const MAX_RESEND_ATTEMPTS_PER_HOUR = 10;

    public function generateAndSend(User $user, ?string $ipAddress = null, ?string $userAgent = null): EmailOtp
    {
        if ($user->hasVerifiedEmail()) throw OtpException::alreadyVerified();

        $rateLimitKey = self::RESEND_RATE_LIMIT_PREFIX . $user->id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_RESEND_ATTEMPTS_PER_HOUR)) {
            throw OtpException::cooldown(RateLimiter::availableIn($rateLimitKey));
        }

        $existingOtp = $this->getLatestOtp($user);
        if ($existingOtp && !$existingOtp->canResend()) {
            throw OtpException::cooldown($existingOtp->getResendCooldownRemaining());
        }

        return DB::transaction(function () use ($user, $ipAddress, $userAgent, $rateLimitKey) {
            $this->invalidatePreviousOtps($user);
            $rawOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $otp = EmailOtp::create([
                'user_id' => $user->id, 'email' => $user->email, 'code' => Hash::make($rawOtp),
                'expires_at' => now()->addMinutes(EmailOtp::EXPIRATION_MINUTES),
                'resend_available_at' => now()->addSeconds(EmailOtp::RESEND_COOLDOWN_SECONDS),
                'ip_address' => $ipAddress, 'user_agent' => $userAgent,
            ]);

            RateLimiter::hit($rateLimitKey, 3600);
            $user->notify(new OtpNotification($rawOtp, EmailOtp::EXPIRATION_MINUTES));
            Log::info('OTP generated', ['user_id' => $user->id, 'email' => $user->email]);
            return $otp;
        });
    }

    public function verify(User $user, string $code, ?string $ipAddress = null): bool
    {
        if ($user->hasVerifiedEmail()) throw OtpException::alreadyVerified();

        $rateLimitKey = self::VERIFY_RATE_LIMIT_PREFIX . $user->id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_VERIFY_ATTEMPTS_PER_MINUTE)) {
            throw OtpException::cooldown(RateLimiter::availableIn($rateLimitKey));
        }

        $otp = $this->getLatestValidOtp($user);
        if (!$otp) { RateLimiter::hit($rateLimitKey, 60); throw OtpException::notFound(); }
        if ($otp->isExpired()) throw OtpException::expired();
        if ($otp->hasExceededMaxAttempts()) throw OtpException::maxAttempts();

        $otp->incrementAttempts();
        RateLimiter::hit($rateLimitKey, 60);

        if (!Hash::check($code, $otp->code)) {
            $remaining = $otp->getRemainingAttempts();
            if ($remaining <= 0) throw OtpException::maxAttempts();
            throw OtpException::invalid($remaining);
        }

        return DB::transaction(function () use ($user, $otp) {
            $otp->markAsVerified();
            $user->markEmailAsVerified();
            RateLimiter::clear(self::VERIFY_RATE_LIMIT_PREFIX . $user->id);
            RateLimiter::clear(self::RESEND_RATE_LIMIT_PREFIX . $user->id);
            Log::info('OTP verified successfully', ['user_id' => $user->id]);
            return true;
        });
    }

    public function resend(User $user, ?string $ipAddress = null, ?string $userAgent = null): EmailOtp
    {
        return $this->generateAndSend($user, $ipAddress, $userAgent);
    }

    public function getLatestOtp(User $user): ?EmailOtp { return EmailOtp::forUser($user->id)->latest()->first(); }
    public function getLatestValidOtp(User $user): ?EmailOtp { return EmailOtp::forUser($user->id)->valid()->latest()->first(); }
    public function canResend(User $user): bool { if ($user->hasVerifiedEmail()) return false; $otp = $this->getLatestOtp($user); return !$otp || $otp->canResend(); }
    public function getResendCooldownRemaining(User $user): int { $otp = $this->getLatestOtp($user); return $otp ? $otp->getResendCooldownRemaining() : 0; }

    public function getVerificationStatus(User $user): array
    {
        $otp = $this->getLatestOtp($user);
        if (!$otp) return ['has_otp' => false, 'is_expired' => false, 'can_resend' => true, 'resend_cooldown' => 0, 'remaining_attempts' => EmailOtp::MAX_ATTEMPTS, 'expires_in_seconds' => 0];
        return [
            'has_otp' => true, 'is_expired' => $otp->isExpired(), 'can_resend' => $otp->canResend(),
            'resend_cooldown' => $otp->getResendCooldownRemaining(), 'remaining_attempts' => $otp->getRemainingAttempts(),
            'expires_in_seconds' => max(0, $otp->isExpired() ? 0 : (int) now()->diffInSeconds($otp->expires_at, false)),
        ];
    }

    private function invalidatePreviousOtps(User $user): void { EmailOtp::forUser($user->id)->whereNull('verified_at')->update(['expires_at' => now()]); }
}
