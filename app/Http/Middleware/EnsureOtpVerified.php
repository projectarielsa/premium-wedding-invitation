<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) return $next($request);

        if ($user->needsOtpVerification()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email verification required.', 'verification_url' => route('verification.otp.show')], 403);
            }
            return redirect()->route('verification.otp.show')->with('warning', 'Please verify your email to access this page.');
        }

        return $next($request);
    }
}
