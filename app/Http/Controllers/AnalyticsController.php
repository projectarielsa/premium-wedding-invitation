<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Invitation;
use App\Models\InvitationAnalytic;
use App\Services\PackageLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Controller for invitation analytics and statistics.
 *
 * Provides comprehensive analytics including page views, visitor stats,
 * RSVP analytics, device breakdowns, and chart data for visualizations.
 */
class AnalyticsController extends Controller
{
    public function __construct(
        private readonly PackageLimitService $packageLimitService
    ) {}

    /**
     * Display analytics overview for an invitation.
     */
    public function index(Request $request, Invitation $invitation): View
    {
        $this->authorize('viewAnalytics', $invitation);

        $user = $request->user();
        
        // Admin bypass - skip feature check for admins
        if (!$user->isAdmin()) {
            // Check if analytics feature is enabled for user's package
            $featureCheck = $this->packageLimitService->canAccessAnalytics($user);
            
            if (!$featureCheck->isAllowed()) {
                return view('analytics.locked', [
                    'invitation' => $invitation,
                    'message' => $featureCheck->message,
                    'upgradeRequired' => $featureCheck->needsUpgrade(),
                    'suggestedPackage' => $this->packageLimitService->getUpgradeSuggestion($user, 'analytics'),
                ]);
            }
        }

        $period = $request->input('period', '7days');
        $dateRange = $this->getDateRange($period);

        // Get overview stats
        $overview = $this->getOverviewStats($invitation);

        // Get RSVP stats
        $rsvpStats = $invitation->getRsvpStats();

        // Get visitor stats for the period
        $visitorStats = $this->getVisitorStats($invitation, $dateRange);

        // Get top referrers
        $topReferrers = $this->getTopReferrers($invitation, $dateRange);

        // Get device breakdown
        $deviceBreakdown = $this->getDeviceBreakdown($invitation, $dateRange);

        return view('analytics.index', compact(
            'invitation',
            'overview',
            'rsvpStats',
            'visitorStats',
            'topReferrers',
            'deviceBreakdown',
            'period'
        ));
    }

    /**
     * Get overview statistics for the invitation.
     */
    private function getOverviewStats(Invitation $invitation): array
    {
        return [
            'total_views' => $invitation->view_count,
            'unique_visitors' => $invitation->unique_visitor_count,
            'total_guests' => $invitation->guests()->count(),
            'total_rsvps' => $invitation->rsvps()->whereNotNull('responded_at')->count(),
            'total_events' => $invitation->events()->count(),
            'days_until_event' => $invitation->days_until_event,
        ];
    }



    /**
     * Get RSVP statistics for the invitation.
     */
    public function rsvpStats(Request $request, Invitation $invitation): JsonResponse
    {
        $this->authorize('viewAnalytics', $invitation);

        $stats = $invitation->getRsvpStats();

        // Get RSVP trend over time
        $trend = $invitation->rsvps()
            ->whereNotNull('responded_at')
            ->select(
                DB::raw('DATE(responded_at) as date'),
                DB::raw('COUNT(*) as count'),
                'attendance_status'
            )
            ->groupBy('date', 'attendance_status')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($dayData) {
                $result = ['date' => $dayData->first()->date];
                foreach ($dayData as $item) {
                    $result[$item->attendance_status] = $item->count;
                }
                return $result;
            })
            ->values();

        // Get by category breakdown
        $byCategory = DB::table('rsvps')
            ->join('guests', 'rsvps.guest_id', '=', 'guests.id')
            ->where('rsvps.invitation_id', $invitation->id)
            ->whereNotNull('rsvps.responded_at')
            ->select(
                'guests.category',
                'rsvps.attendance_status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(rsvps.attendance_count) as total_attendees')
            )
            ->groupBy('guests.category', 'rsvps.attendance_status')
            ->get();

        return response()->json([
            'stats' => $stats,
            'trend' => $trend,
            'by_category' => $byCategory,
        ]);
    }

    /**
     * Get visitor statistics for the invitation.
     */
    public function visitorStats(Request $request, Invitation $invitation): JsonResponse
    {
        $this->authorize('viewAnalytics', $invitation);

        $period = $request->input('period', '7days');
        $dateRange = $this->getDateRange($period);

        $stats = $this->getVisitorStats($invitation, $dateRange);

        return response()->json($stats);
    }

    /**
     * Get visitor statistics for date range.
     */
    private function getVisitorStats(Invitation $invitation, array $dateRange): array
    {
        $analytics = $invitation->analytics()
            ->dateRange($dateRange['start'], $dateRange['end'])
            ->orderByDate()
            ->get();

        $totals = [
            'page_views' => $analytics->sum('page_views'),
            'unique_visitors' => $analytics->sum('unique_visitors'),
            'rsvp_submissions' => $analytics->sum('rsvp_submissions'),
            'whatsapp_shares' => $analytics->sum('whatsapp_shares'),
            'gift_copy_clicks' => $analytics->sum('gift_copy_clicks'),
            'map_clicks' => $analytics->sum('map_clicks'),
        ];

        $daily = $analytics->map(fn ($a) => [
            'date' => $a->date->format('Y-m-d'),
            'page_views' => $a->page_views,
            'unique_visitors' => $a->unique_visitors,
            'rsvp_submissions' => $a->rsvp_submissions,
            'guest_opens' => $a->guest_opens,
            'anonymous_opens' => $a->anonymous_opens,
        ]);

        return [
            'totals' => $totals,
            'daily' => $daily,
            'period' => [
                'start' => $dateRange['start']->format('Y-m-d'),
                'end' => $dateRange['end']->format('Y-m-d'),
            ],
        ];
    }



    /**
     * Get chart data for analytics visualizations.
     */
    public function chartData(Request $request, Invitation $invitation): JsonResponse
    {
        $this->authorize('viewAnalytics', $invitation);

        $period = $request->input('period', '7days');
        $chartType = $request->input('chart', 'visitors');
        $dateRange = $this->getDateRange($period);

        $data = match ($chartType) {
            'visitors' => $this->getVisitorChartData($invitation, $dateRange),
            'rsvp' => $this->getRsvpChartData($invitation),
            'engagement' => $this->getEngagementChartData($invitation, $dateRange),
            'devices' => $this->getDeviceBreakdown($invitation, $dateRange),
            'referrers' => $this->getTopReferrers($invitation, $dateRange),
            default => [],
        };

        return response()->json($data);
    }

    /**
     * Get visitor chart data.
     */
    private function getVisitorChartData(Invitation $invitation, array $dateRange): array
    {
        $analytics = $invitation->analytics()
            ->dateRange($dateRange['start'], $dateRange['end'])
            ->orderByDate()
            ->get();

        return [
            'labels' => $analytics->pluck('date')->map(fn ($d) => $d->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $analytics->pluck('page_views')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Unique Visitors',
                    'data' => $analytics->pluck('unique_visitors')->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
        ];
    }

    /**
     * Get RSVP chart data.
     */
    private function getRsvpChartData(Invitation $invitation): array
    {
        $stats = $invitation->getRsvpStats();

        return [
            'labels' => ['Attending', 'Not Attending', 'Maybe', 'Pending'],
            'datasets' => [[
                'data' => [
                    $stats['attending'],
                    $stats['not_attending'],
                    $stats['maybe'],
                    $stats['pending'],
                ],
                'backgroundColor' => ['#10B981', '#EF4444', '#F59E0B', '#6B7280'],
            ]],
            'total_invited' => $stats['total_invited'],
            'estimated_attendance' => $stats['attending_guests'],
        ];
    }

    /**
     * Get engagement chart data.
     */
    private function getEngagementChartData(Invitation $invitation, array $dateRange): array
    {
        $analytics = $invitation->analytics()
            ->dateRange($dateRange['start'], $dateRange['end'])
            ->orderByDate()
            ->get();

        return [
            'labels' => $analytics->pluck('date')->map(fn ($d) => $d->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'RSVP Submissions',
                    'data' => $analytics->pluck('rsvp_submissions')->toArray(),
                    'borderColor' => '#8B5CF6',
                ],
                [
                    'label' => 'WhatsApp Shares',
                    'data' => $analytics->pluck('whatsapp_shares')->toArray(),
                    'borderColor' => '#22C55E',
                ],
                [
                    'label' => 'Gift Clicks',
                    'data' => $analytics->pluck('gift_copy_clicks')->toArray(),
                    'borderColor' => '#F59E0B',
                ],
            ],
        ];
    }



    /**
     * Get device breakdown statistics.
     */
    private function getDeviceBreakdown(Invitation $invitation, array $dateRange): array
    {
        $analytics = $invitation->analytics()
            ->dateRange($dateRange['start'], $dateRange['end'])
            ->get();

        $deviceStats = [];
        foreach ($analytics as $analytic) {
            if ($analytic->device_stats) {
                foreach ($analytic->device_stats as $device => $count) {
                    $deviceStats[$device] = ($deviceStats[$device] ?? 0) + $count;
                }
            }
        }

        // Sort by count descending
        arsort($deviceStats);

        return [
            'labels' => array_keys($deviceStats),
            'data' => array_values($deviceStats),
            'total' => array_sum($deviceStats),
        ];
    }

    /**
     * Get top referrers.
     */
    private function getTopReferrers(Invitation $invitation, array $dateRange): array
    {
        $analytics = $invitation->analytics()
            ->dateRange($dateRange['start'], $dateRange['end'])
            ->get();

        $referrerStats = [];
        foreach ($analytics as $analytic) {
            if ($analytic->referral_stats) {
                foreach ($analytic->referral_stats as $referrer => $count) {
                    $referrerStats[$referrer] = ($referrerStats[$referrer] ?? 0) + $count;
                }
            }
        }

        // Sort by count descending and take top 10
        arsort($referrerStats);
        $referrerStats = array_slice($referrerStats, 0, 10, true);

        return [
            'labels' => array_keys($referrerStats),
            'data' => array_values($referrerStats),
        ];
    }

    /**
     * Get date range from period string.
     */
    private function getDateRange(string $period): array
    {
        $end = Carbon::today();

        $start = match ($period) {
            '24hours' => Carbon::today(),
            '7days' => Carbon::today()->subDays(6),
            '30days' => Carbon::today()->subDays(29),
            '90days' => Carbon::today()->subDays(89),
            'all' => Carbon::create(2020, 1, 1),
            default => Carbon::today()->subDays(6),
        };

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Track a public analytics event (called via AJAX from public pages).
     */
    public function track(Request $request, string $slug): JsonResponse
    {
        $invitation = Invitation::where('slug', $slug)->first();

        if (!$invitation || !$invitation->isPublic()) {
            return response()->json(['success' => false], 404);
        }

        $request->validate([
            'event' => ['required', 'string', 'in:page_view,gift_view,gift_copy,map_click,whatsapp_share,link_copy,gallery_view'],
            'is_unique' => ['nullable', 'boolean'],
            'is_guest' => ['nullable', 'boolean'],
            'device' => ['nullable', 'string', 'max:50'],
            'referrer' => ['nullable', 'string', 'max:255'],
        ]);

        $analytic = InvitationAnalytic::getOrCreateForToday($invitation->id);
        $event = $request->input('event');

        match ($event) {
            'page_view' => $analytic->recordPageView(
                $request->boolean('is_unique'),
                $request->boolean('is_guest')
            ),
            'gift_view' => $analytic->incrementMetric('gift_section_views'),
            'gift_copy' => $analytic->incrementMetric('gift_copy_clicks'),
            'map_click' => $analytic->incrementMetric('map_clicks'),
            'whatsapp_share' => $analytic->incrementMetric('whatsapp_shares'),
            'link_copy' => $analytic->incrementMetric('link_copies'),
            'gallery_view' => $analytic->incrementMetric('gallery_views'),
            default => null,
        };

        // Record device if provided
        if ($request->filled('device')) {
            $analytic->recordDevice($request->input('device'));
        }

        // Record referrer if provided
        if ($request->filled('referrer')) {
            $analytic->recordReferral($request->input('referrer'));
        }

        return response()->json(['success' => true]);
    }
}
