<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationAnalytic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to track invitation page views and analytics.
 *
 * Tracks:
 * - Page views
 * - Unique visitors (via cookie)
 * - Device type
 * - Referral source
 * - Guest vs anonymous opens
 */
class TrackInvitationView
{
    /**
     * Cookie name for tracking unique visitors.
     */
    protected const VISITOR_COOKIE = 'invitation_visitor';

    /**
     * Cookie lifetime in minutes (30 days).
     */
    protected const COOKIE_LIFETIME = 43200;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful responses
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        // Get invitation from route
        $invitation = $this->getInvitationFromRoute($request);
        if (! $invitation) {
            return $response;
        }

        // Get guest if present
        $guest = $this->getGuestFromRoute($request);

        // Track the view
        $this->trackView($request, $invitation, $guest);

        // Set visitor cookie if not present
        if (! $request->cookie(self::VISITOR_COOKIE)) {
            $visitorId = $this->generateVisitorId();
            Cookie::queue(
                self::VISITOR_COOKIE,
                $visitorId,
                self::COOKIE_LIFETIME
            );
        }

        return $response;
    }



    /**
     * Get invitation from route parameters.
     */
    protected function getInvitationFromRoute(Request $request): ?Invitation
    {
        $invitation = $request->route('invitation');

        if ($invitation instanceof Invitation) {
            return $invitation;
        }

        // If slug is passed, resolve it
        if (is_string($invitation)) {
            return Invitation::where('slug', $invitation)->first();
        }

        // Try to get from 'slug' parameter
        $slug = $request->route('slug');
        if ($slug) {
            return Invitation::where('slug', $slug)->first();
        }

        return null;
    }

    /**
     * Get guest from route parameters.
     */
    protected function getGuestFromRoute(Request $request): ?Guest
    {
        $guest = $request->route('guest');

        if ($guest instanceof Guest) {
            return $guest;
        }

        // If guest token is passed, resolve it
        if (is_string($guest)) {
            return Guest::where('slug_token', $guest)->first();
        }

        return null;
    }

    /**
     * Track the page view.
     */
    protected function trackView(Request $request, Invitation $invitation, ?Guest $guest): void
    {
        // Check if unique visitor
        $isUnique = ! $request->cookie(self::VISITOR_COOKIE);

        // Get or create today's analytics record
        $analytic = InvitationAnalytic::getOrCreateForToday($invitation->id);

        // Record page view
        $analytic->recordPageView($isUnique, $guest !== null);

        // Record device type
        $deviceType = $this->detectDeviceType($request);
        $analytic->recordDevice($deviceType);

        // Record referral source
        $referral = $this->detectReferralSource($request);
        $analytic->recordReferral($referral);

        // Update invitation counters
        $invitation->incrementViewCount();
        if ($isUnique) {
            $invitation->incrementUniqueVisitorCount();
        }

        // If guest, record their visit
        if ($guest) {
            $guest->recordVisit();
        }
    }



    /**
     * Detect device type from user agent.
     */
    protected function detectDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile/i', $userAgent)) {
            return 'mobile';
        }

        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Detect referral source.
     */
    protected function detectReferralSource(Request $request): string
    {
        $referer = strtolower($request->header('referer', ''));

        if (empty($referer)) {
            return 'direct';
        }

        // Check for common referral sources
        $sources = [
            'whatsapp' => ['whatsapp', 'wa.me'],
            'instagram' => ['instagram', 'ig.me'],
            'facebook' => ['facebook', 'fb.com', 'fb.me'],
            'twitter' => ['twitter', 't.co', 'x.com'],
            'telegram' => ['telegram', 't.me'],
            'tiktok' => ['tiktok'],
            'line' => ['line.me'],
            'google' => ['google'],
        ];

        foreach ($sources as $source => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($referer, $pattern)) {
                    return $source;
                }
            }
        }

        return 'other';
    }

    /**
     * Generate unique visitor ID.
     */
    protected function generateVisitorId(): string
    {
        return bin2hex(random_bytes(16));
    }
}
