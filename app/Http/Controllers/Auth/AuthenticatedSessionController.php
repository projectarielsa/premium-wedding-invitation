<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function create(): View { return view('auth.login'); }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = $request->user();

        if ($user->needsOtpVerification()) {
            try {
                $status = $this->otpService->getVerificationStatus($user);
                if (!$status['has_otp'] || $status['is_expired']) {
                    $this->otpService->generateAndSend($user, $request->ip(), $request->userAgent());
                }
            } catch (\Exception $e) {
                Log::warning('Failed to generate OTP on login', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
            return redirect(route('verification.otp.show'))->with('status', 'Please verify your email to continue.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
