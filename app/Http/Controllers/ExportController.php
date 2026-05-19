<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\PackageLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for exporting invitation data.
 * Handles CSV exports for guests, RSVPs, and analytics.
 */
class ExportController extends Controller
{
    public function __construct(
        private readonly PackageLimitService $packageLimitService
    ) {}

    /**
     * Export guests to CSV.
     */
    public function guests(Request $request, Invitation $invitation): StreamedResponse
    {
        $this->authorize('view', $invitation);

        $user = $request->user();
        
        // Admin bypass - skip feature check for admins
        if (!$user->isAdmin()) {
            // Check if export feature is enabled for user's package
            $featureCheck = $this->packageLimitService->canExportData($user);
            
            if (!$featureCheck->isAllowed()) {
                abort(403, $featureCheck->message);
            }
        }

        $guests = $invitation->guests()->with('rsvp')->get();
        $filename = Str::slug($invitation->couple_name) . '-guests-' . now()->format('Y-m-d') . '.csv';

        return $this->streamCsv($filename, function ($handle) use ($guests) {
            // Header row
            fputcsv($handle, [
                'Name',
                'Phone Number',
                'WhatsApp',
                'Email',
                'Category',
                'Max Attendees',
                'Notes',
                'RSVP Status',
                'Attendance Count',
                'RSVP Message',
                'Personal Link',
                'WhatsApp Sent',
                'Checked In',
                'Checked In By',
                'Visit Count',
                'First Visited',
                'Last Visited',
            ]);

            foreach ($guests as $guest) {
                fputcsv($handle, [
                    $guest->name,
                    $guest->phone_number,
                    $guest->whatsapp,
                    $guest->email,
                    $guest->category->label(),
                    $guest->max_attendees,
                    $guest->notes,
                    $guest->rsvp?->attendance_status?->label() ?? 'Pending',
                    $guest->rsvp?->attendance_count ?? 0,
                    $guest->rsvp?->message,
                    $guest->personalized_url,
                    $guest->whatsapp_sent_at?->format('Y-m-d H:i'),
                    $guest->checked_in_at?->format('Y-m-d H:i'),
                    $guest->checked_in_by,
                    $guest->unique_visit_count,
                    $guest->first_visited_at?->format('Y-m-d H:i'),
                    $guest->last_visited_at?->format('Y-m-d H:i'),
                ]);
            }
        });
    }

    /**
     * Export RSVPs to CSV.
     */
    public function rsvps(Request $request, Invitation $invitation): StreamedResponse
    {
        $this->authorize('view', $invitation);

        $user = $request->user();
        
        // Admin bypass - skip feature check for admins
        if (!$user->isAdmin()) {
            // Check if export feature is enabled for user's package
            $featureCheck = $this->packageLimitService->canExportData($user);
            
            if (!$featureCheck->isAllowed()) {
                abort(403, $featureCheck->message);
            }
        }

        $rsvps = $invitation->rsvps()->with('guest')->get();
        $filename = Str::slug($invitation->couple_name) . '-rsvps-' . now()->format('Y-m-d') . '.csv';

        return $this->streamCsv($filename, function ($handle) use ($rsvps) {
            // Header row
            fputcsv($handle, [
                'Guest Name',
                'Guest Phone',
                'Guest Email',
                'Guest Category',
                'Status',
                'Attendance Count',
                'Message',
                'Dietary Requirements',
                'Responded At',
                'IP Address',
                'User Agent',
            ]);

            foreach ($rsvps as $rsvp) {
                fputcsv($handle, [
                    $rsvp->guest?->name ?? $rsvp->guest_name ?? 'Anonymous',
                    $rsvp->guest?->phone_number,
                    $rsvp->guest?->email,
                    $rsvp->guest?->category?->label(),
                    $rsvp->attendance_status?->label() ?? 'Pending',
                    $rsvp->attendance_count,
                    $rsvp->message,
                    $rsvp->dietary_requirements,
                    $rsvp->responded_at?->format('Y-m-d H:i'),
                    $rsvp->ip_address,
                    Str::limit($rsvp->user_agent, 50),
                ]);
            }
        });
    }

    /**
     * Export analytics to CSV.
     */
    public function analytics(Request $request, Invitation $invitation): StreamedResponse
    {
        $this->authorize('view', $invitation);

        $user = $request->user();
        
        // Admin bypass - skip feature check for admins
        if (!$user->isAdmin()) {
            // Check if export feature is enabled for user's package
            $featureCheck = $this->packageLimitService->canExportData($user);
            
            if (!$featureCheck->isAllowed()) {
                abort(403, $featureCheck->message);
            }
        }

        $analytics = $invitation->analytics()
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        $filename = Str::slug($invitation->couple_name) . '-analytics-' . now()->format('Y-m-d') . '.csv';

        return $this->streamCsv($filename, function ($handle) use ($analytics) {
            // Header row
            fputcsv($handle, [
                'Date/Time',
                'Event Type',
                'Guest Name',
                'Section',
                'IP Address',
                'Device Type',
                'Browser',
                'Referrer',
                'Country',
                'City',
            ]);

            foreach ($analytics as $analytic) {
                fputcsv($handle, [
                    $analytic->created_at->format('Y-m-d H:i:s'),
                    $analytic->event_type,
                    $analytic->guest?->name ?? 'Anonymous',
                    $analytic->section_viewed,
                    $analytic->ip_address,
                    $analytic->device_type,
                    $analytic->browser,
                    $analytic->referrer,
                    $analytic->country,
                    $analytic->city,
                ]);
            }
        });
    }

    /**
     * Export complete summary report.
     */
    public function summary(Request $request, Invitation $invitation): StreamedResponse
    {
        $this->authorize('view', $invitation);

        $user = $request->user();
        
        // Admin bypass - skip feature check for admins
        if (!$user->isAdmin()) {
            // Check if export feature is enabled for user's package
            $featureCheck = $this->packageLimitService->canExportData($user);
            
            if (!$featureCheck->isAllowed()) {
                abort(403, $featureCheck->message);
            }
        }

        $filename = Str::slug($invitation->couple_name) . '-summary-' . now()->format('Y-m-d') . '.csv';

        $stats = $invitation->getRsvpStats();
        $guestStats = [
            'total' => $invitation->guests()->count(),
            'by_category' => $invitation->guests()
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
            'checked_in' => $invitation->guests()->checkedIn()->count(),
            'visited' => $invitation->guests()->visited()->count(),
            'whatsapp_sent' => $invitation->guests()->whatsappSent()->count(),
        ];

        return $this->streamCsv($filename, function ($handle) use ($invitation, $stats, $guestStats) {
            // Invitation Info
            fputcsv($handle, ['=== INVITATION SUMMARY ===']);
            fputcsv($handle, ['Title', $invitation->title]);
            fputcsv($handle, ['Couple', $invitation->couple_name]);
            fputcsv($handle, ['Event Date', $invitation->event_date?->format('Y-m-d')]);
            fputcsv($handle, ['Status', $invitation->status->label()]);
            fputcsv($handle, ['Total Views', $invitation->view_count]);
            fputcsv($handle, ['Unique Visitors', $invitation->unique_visitor_count]);
            fputcsv($handle, ['']);

            // RSVP Stats
            fputcsv($handle, ['=== RSVP STATISTICS ===']);
            fputcsv($handle, ['Total Invited', $stats['total_invited']]);
            fputcsv($handle, ['Total Responded', $stats['total_responded']]);
            fputcsv($handle, ['Attending', $stats['attending']]);
            fputcsv($handle, ['Total Guests Attending', $stats['attending_guests']]);
            fputcsv($handle, ['Not Attending', $stats['not_attending']]);
            fputcsv($handle, ['Maybe', $stats['maybe']]);
            fputcsv($handle, ['Pending', $stats['pending']]);
            fputcsv($handle, ['']);

            // Guest Stats
            fputcsv($handle, ['=== GUEST STATISTICS ===']);
            fputcsv($handle, ['Total Guests', $guestStats['total']]);
            fputcsv($handle, ['Checked In', $guestStats['checked_in']]);
            fputcsv($handle, ['Visited Invitation', $guestStats['visited']]);
            fputcsv($handle, ['WhatsApp Sent', $guestStats['whatsapp_sent']]);
            fputcsv($handle, ['']);

            // By Category
            fputcsv($handle, ['=== GUESTS BY CATEGORY ===']);
            foreach ($guestStats['by_category'] as $category => $count) {
                fputcsv($handle, [ucfirst($category), $count]);
            }
        });
    }

    /**
     * Stream a CSV response.
     */
    private function streamCsv(string $filename, callable $callback): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($callback) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $callback($handle);
            fclose($handle);
        }, 200, $headers);
    }
}
