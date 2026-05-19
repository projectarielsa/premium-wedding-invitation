<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            Log::warning('Google OAuth failed', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['google' => 'Google authentication failed. Please try again.']);
        }

        if (empty($googleUser->getEmail())) {
            return redirect()->route('login')->withErrors(['google' => 'Could not retrieve email from Google.']);
        }

        try {
            $user = DB::transaction(function () use ($googleUser) {
                $user = User::where('google_id', $googleUser->getId())->first();
                if ($user) { $user->update(['avatar' => $googleUser->getAvatar()]); return $user; }

                $user = User::where('email', $googleUser->getEmail())->first();
                if ($user) {
                    $user->update(['google_id' => $googleUser->getId(), 'avatar' => $googleUser->getAvatar(), 'provider' => 'google', 'email_verified_at' => $user->email_verified_at ?? now()]);
                    return $user;
                }

                return User::create([
                    'name' => $googleUser->getName() ?? explode('@', $googleUser->getEmail())[0],
                    'email' => $googleUser->getEmail(), 'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), 'provider' => 'google', 'email_verified_at' => now(),
                ]);
            });

            Auth::login($user, remember: true);
            return redirect()->intended(route('dashboard', absolute: false))->with('status', 'Welcome! You have been logged in with Google.');
        } catch (Exception $e) {
            Log::error('Failed to process Google OAuth user', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['google' => 'An error occurred during authentication.']);
        }
    }
}
