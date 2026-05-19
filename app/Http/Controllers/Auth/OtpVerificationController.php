<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\OtpException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\Auth\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) return redirect()->intended(route('dashboard', absolute: false));

        $status = $this->otpService->getVerificationStatus($user);
        if (!$status['has_otp'] || $status['is_expired']) {
            try {
                $this->otpService->generateAndSend($user, $request->ip(), $request->userAgent());
                $status = $this->otpService->getVerificationStatus($user);
                session()->flash('status', 'A verification code has been sent to your email.');
            } catch (OtpException $e) { session()->flash('error', $e->getMessage()); }
        }

        return view('auth.verify-otp', [
            'email' => $user->email, 'canResend' => $status['can_resend'], 'resendCooldown' => $status['resend_cooldown'],
            'expiresIn' => $status['expires_in_seconds'], 'remainingAttempts' => $status['remaining_attempts'],
        ]);
    }

    public function verify(VerifyOtpRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) return redirect()->intended(route('dashboard', absolute: false));

        try {
            $this->otpService->verify($user, $request->getOtp(), $request->ip());
            return redirect()->intended(route('dashboard', absolute: false))->with('status', 'Your email has been verified successfully!');
        } catch (OtpException $e) {
            $errorCode = $e->getErrorCode();
            if (in_array($errorCode, [OtpException::CODE_EXPIRED, OtpException::CODE_MAX_ATTEMPTS, OtpException::CODE_NOT_FOUND]))
                return back()->withErrors(['otp' => $e->getMessage()])->with('suggest_resend', true);
            if ($errorCode === OtpException::CODE_INVALID)
                return back()->withErrors(['otp' => $e->getMessage()])->with('remaining_attempts', $e->getContext()['remaining_attempts'] ?? 0);
            if ($errorCode === OtpException::CODE_COOLDOWN)
                return back()->withErrors(['otp' => $e->getMessage()])->with('cooldown', $e->getContext()['seconds_remaining'] ?? 60);
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) return redirect()->intended(route('dashboard', absolute: false));

        try {
            $this->otpService->resend($user, $request->ip(), $request->userAgent());
            return back()->with('status', 'A new verification code has been sent to your email.');
        } catch (OtpException $e) {
            if ($e->getErrorCode() === OtpException::CODE_COOLDOWN)
                return back()->withErrors(['resend' => $e->getMessage()])->with('resend_cooldown', $e->getContext()['seconds_remaining'] ?? 60);
            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
