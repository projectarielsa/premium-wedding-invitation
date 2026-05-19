<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Dashboard controller for premium SaaS wedding invitation platform.
 *
 * Provides comprehensive dashboard data including invitation stats,
 * RSVP analytics, and recent activity for authenticated users.
 */
class DashboardController extends Controller
{
    /**
     * Display the premium dashboard with comprehensive stats.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get invitation statistics
        $invitationStats = $this->getInvitationStats($user);

        // Get RSVP statistics
        $rsvpStats = $this->getRsvpStats($user);

        // Get recent invitations
        $recentInvitations = $user->invitations()
            ->with(['events', 'template'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent RSVPs across all user's invitations
        $recentRsvps = Rsvp::whereIn('invitation_id', $user->invitations()->pluck('id'))
            ->with(['guest', 'invitation'])
            ->whereNotNull('responded_at')
            ->latest('responded_at')
            ->take(10)
            ->get();

        // Get analytics summary
        $analyticsSummary = $this->getAnalyticsSummary($user);

        return view('dashboard', compact(
            'invitationStats',
            'rsvpStats',
            'recentInvitations',
            'recentRsvps',
            'analyticsSummary'
        ));
    }



    /**
     * Get invitation statistics for the user.
     *
     * @param \App\Models\User $user
     * @return array<string, int>
     */
    private function getInvitationStats($user): array
    {
        $invitations = $user->invitations();

        return [
            'total' => $invitations->count(),
            'published' => $invitations->clone()->where('status', InvitationStatus::Published)->count(),
            'draft' => $invitations->clone()->where('status', InvitationStatus::Draft)->count(),
            'archived' => $invitations->clone()->where('status', InvitationStatus::Archived)->count(),
            'total_views' => $invitations->clone()->sum('view_count'),
            'unique_visitors' => $invitations->clone()->sum('unique_visitor_count'),
        ];
    }

    /**
     * Get RSVP statistics across all user's invitations.
     *
     * @param \App\Models\User $user
     * @return array<string, int>
     */
    private function getRsvpStats($user): array
    {
        $invitationIds = $user->invitations()->pluck('id');

        // Get total guests count
        $totalGuests = DB::table('guests')
            ->whereIn('invitation_id', $invitationIds)
            ->whereNull('deleted_at')
            ->count();

        // Get RSVP stats by status
        $rsvpCounts = Rsvp::whereIn('invitation_id', $invitationIds)
            ->select('attendance_status', DB::raw('COUNT(*) as count'), DB::raw('SUM(attendance_count) as total_attendees'))
            ->groupBy('attendance_status')
            ->get()
            ->keyBy('attendance_status');

        $attending = $rsvpCounts->get(AttendanceStatus::Attending->value);
        $notAttending = $rsvpCounts->get(AttendanceStatus::NotAttending->value);
        $maybe = $rsvpCounts->get(AttendanceStatus::Maybe->value);

        return [
            'total_guests' => $totalGuests,
            'total_rsvps' => Rsvp::whereIn('invitation_id', $invitationIds)
                ->whereNotNull('responded_at')
                ->count(),
            'attending' => $attending?->count ?? 0,
            'estimated_attendance' => $attending?->total_attendees ?? 0,
            'not_attending' => $notAttending?->count ?? 0,
            'maybe' => $maybe?->count ?? 0,
            'pending' => $totalGuests - (($attending?->count ?? 0) + ($notAttending?->count ?? 0) + ($maybe?->count ?? 0)),
        ];
    }



    /**
     * Get analytics summary for the user.
     *
     * @param \App\Models\User $user
     * @return array<string, mixed>
     */
    private function getAnalyticsSummary($user): array
    {
        $invitationIds = $user->invitations()->pluck('id');

        // Get last 7 days analytics
        $weeklyStats = DB::table('invitation_analytics')
            ->whereIn('invitation_id', $invitationIds)
            ->where('date', '>=', now()->subDays(6)->toDateString())
            ->select(
                DB::raw('SUM(page_views) as page_views'),
                DB::raw('SUM(unique_visitors) as unique_visitors'),
                DB::raw('SUM(rsvp_submissions) as rsvp_submissions'),
                DB::raw('SUM(whatsapp_shares) as whatsapp_shares')
            )
            ->first();

        // Get today's stats
        $todayStats = DB::table('invitation_analytics')
            ->whereIn('invitation_id', $invitationIds)
            ->where('date', now()->toDateString())
            ->select(
                DB::raw('SUM(page_views) as page_views'),
                DB::raw('SUM(unique_visitors) as unique_visitors'),
                DB::raw('SUM(rsvp_submissions) as rsvp_submissions')
            )
            ->first();

        return [
            'weekly' => [
                'page_views' => $weeklyStats?->page_views ?? 0,
                'unique_visitors' => $weeklyStats?->unique_visitors ?? 0,
                'rsvp_submissions' => $weeklyStats?->rsvp_submissions ?? 0,
                'whatsapp_shares' => $weeklyStats?->whatsapp_shares ?? 0,
            ],
            'today' => [
                'page_views' => $todayStats?->page_views ?? 0,
                'unique_visitors' => $todayStats?->unique_visitors ?? 0,
                'rsvp_submissions' => $todayStats?->rsvp_submissions ?? 0,
            ],
        ];
    }
}
