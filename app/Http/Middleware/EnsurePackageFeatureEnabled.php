<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\PackageLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure user's package allows access to specific features.
 * 
 * Admin and super_admin users bypass all package restrictions.
 * 
 * Usage in routes:
 * Route::get('/analytics', ...)->middleware('package.feature:analytics');
 * Route::get('/export', ...)->middleware('package.feature:export');
 */
class EnsurePackageFeatureEnabled
{
    public function __construct(
        private readonly PackageLimitService $packageLimitService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature  The feature to check (e.g., 'analytics', 'export', 'qr_checkin', 'gift')
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Admin bypass - admins can access all features
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user's package allows this feature
        $result = $this->packageLimitService->canUseFeature($user, $feature);

        if (!$result->isAllowed()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $result->message,
                    'upgrade_required' => $result->needsUpgrade(),
                    'feature' => $feature,
                ], 403);
            }

            // Redirect to pricing with upgrade message
            return redirect()
                ->route('pricing')
                ->with('upgrade_required', true)
                ->with('upgrade_feature', $feature)
                ->with('upgrade_message', $result->message);
        }

        return $next($request);
    }
}
