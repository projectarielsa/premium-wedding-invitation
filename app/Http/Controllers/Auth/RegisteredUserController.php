<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function create(): View { return view('auth.register'); }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)]);
        event(new Registered($user));
        Auth::login($user);

        try {
            $this->otpService->generateAndSend($user, $request->ip(), $request->userAgent());
        } catch (\Exception $e) {
            Log::error('Failed to send OTP after registration', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        return redirect(route('verification.otp.show'))->with('status', 'Please check your email for the verification code.');
    }
}
