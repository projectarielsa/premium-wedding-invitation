<?php

namespace App\Exceptions;

use Exception;

class OtpException extends Exception
{
    public const CODE_EXPIRED = 'otp_expired';
    public const CODE_INVALID = 'otp_invalid';
    public const CODE_MAX_ATTEMPTS = 'otp_max_attempts';
    public const CODE_COOLDOWN = 'otp_cooldown';
    public const CODE_NOT_FOUND = 'otp_not_found';
    public const CODE_ALREADY_VERIFIED = 'otp_already_verified';

    protected string $errorCode;
    protected array $context;

    public function __construct(string $message, string $errorCode, array $context = []) { parent::__construct($message); $this->errorCode = $errorCode; $this->context = $context; }
    public function getErrorCode(): string { return $this->errorCode; }
    public function getContext(): array { return $this->context; }

    public static function expired(): self { return new self('The verification code has expired. Please request a new one.', self::CODE_EXPIRED); }
    public static function invalid(int $remainingAttempts): self { return new self("Invalid verification code. You have {$remainingAttempts} attempts remaining.", self::CODE_INVALID, ['remaining_attempts' => $remainingAttempts]); }
    public static function maxAttempts(): self { return new self('Too many failed attempts. Please request a new verification code.', self::CODE_MAX_ATTEMPTS); }
    public static function cooldown(int $secondsRemaining): self { return new self("Please wait {$secondsRemaining} seconds before requesting a new code.", self::CODE_COOLDOWN, ['seconds_remaining' => $secondsRemaining]); }
    public static function notFound(): self { return new self('No verification code found. Please request a new one.', self::CODE_NOT_FOUND); }
    public static function alreadyVerified(): self { return new self('Your email has already been verified.', self::CODE_ALREADY_VERIFIED); }
}
