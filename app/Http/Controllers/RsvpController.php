<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Exceptions\InvitationException;
use App\Http\Requests\SubmitRsvpRequest;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationAnalytic;
use App\Models\Rsvp;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Controller for public RSVP submissions.
 *
 * Handles RSVP form display and submission for wedding guests.
 * Supports both authenticated guest tokens and anonymous RSVPs.
 */
class RsvpController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {}

    /**
     * Display the RSVP form for an invitation.
     */
    public function show(string $slug, ?string $guestToken = null): View
    {
        try {
            $invitation = $this->invitationService->getPublicBySlug($slug);
        } catch (InvitationException $e) {
            abort(404, $e->getMessage());
        }

        // Check if RSVP is enabled
        if (!$invitation->rsvp_enabled) {
            abort(404, 'RSVP is not available for this invitation.');
        }

        $guest = null;
        $existingRsvp = null;

        if ($guestToken) {
            $guest = $invitation->guests()
                ->where('slug_token', $guestToken)
                ->first();

            if ($guest) {
                $existingRsvp = $guest->rsvp;
            }
        }

        return view('rsvp.show', [
            'invitation' => $invitation,
            'guest' => $guest,
            'existingRsvp' => $existingRsvp,
            'attendanceOptions' => AttendanceStatus::responseOptions(),
            'maxAttendees' => $guest?->max_attendees ?? $invitation->max_attendance_per_guest,
        ]);
    }



    /**
     * Submit an RSVP response.
     */
    public function submit(SubmitRsvpRequest $request, Invitation $invitation): RedirectResponse|JsonResponse
    {
        // Always expect JSON for API calls
        $isJson = $request->expectsJson() || $request->isJson() || $request->ajax();

        // Verify invitation is published and RSVP enabled
        if (!$invitation->isPublic()) {
            if ($isJson) {
                return response()->json(['error' => 'Invitation is not available.'], 404);
            }
            abort(404, 'Invitation is not available.');
        }

        if (!$invitation->rsvp_enabled) {
            if ($isJson) {
                return response()->json(['error' => 'RSVP is not available for this invitation.'], 404);
            }
            abort(404, 'RSVP is not available for this invitation.');
        }

        $guestToken = $request->input('guest_token');
        $guest = null;

        if ($guestToken) {
            $guest = $invitation->guests()
                ->where('slug_token', $guestToken)
                ->first();
        }

        // If no guest token provided, create anonymous RSVP with name
        if (!$guest && $request->filled('guest_name')) {
            $guest = $invitation->guests()->create([
                'name' => $request->input('guest_name'),
                'phone_number' => $request->input('phone_number'),
                'email' => $request->input('email'),
                'max_attendees' => $invitation->max_attendance_per_guest,
            ]);
        }

        if (!$guest) {
            $message = 'Guest information is required to submit RSVP.';
            if ($isJson) {
                return response()->json(['error' => $message], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        // Check for duplicate submission (spam prevention)
        $existingRsvp = Rsvp::where('guest_id', $guest->id)
            ->where('invitation_id', $invitation->id)
            ->first();

        try {
            DB::transaction(function () use ($request, $invitation, $guest) {
                $status = AttendanceStatus::from($request->input('attendance_status'));
                $attendanceCount = $status->isConfirmed() 
                    ? min($request->integer('attendance_count', 1), $guest->max_attendees)
                    : 0;

                // Create or update RSVP
                $rsvp = Rsvp::updateOrCreate(
                    [
                        'guest_id' => $guest->id,
                        'invitation_id' => $invitation->id,
                    ],
                    [
                        'attendance_status' => $status,
                        'attendance_count' => $attendanceCount,
                        'message' => $request->input('message'),
                        'dietary_requirements' => $request->input('dietary_requirements'),
                        'special_requests' => $request->input('special_requests'),
                        'responded_at' => now(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                );

                // Track RSVP submission in analytics
                $analytic = InvitationAnalytic::getOrCreateForToday($invitation->id);
                $analytic->incrementMetric('rsvp_submissions');
            });

            $message = 'Thank you! Your RSVP has been submitted successfully.';

            if ($isJson) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Failed to submit RSVP. Please try again.';

            if ($isJson) {
                return response()->json(['error' => $message], 500);
            }

            return back()->with('error', $message)->withInput();
        }
    }



    /**
     * Get RSVP status for a guest (JSON endpoint).
     */
    public function status(Request $request, Invitation $invitation, string $guestToken): JsonResponse
    {
        if (!$invitation->isPublic()) {
            return response()->json(['error' => 'Invitation not found.'], 404);
        }

        $guest = $invitation->guests()
            ->where('slug_token', $guestToken)
            ->with('rsvp')
            ->first();

        if (!$guest) {
            return response()->json(['error' => 'Guest not found.'], 404);
        }

        return response()->json([
            'guest_name' => $guest->name,
            'has_responded' => $guest->has_responded,
            'rsvp' => $guest->rsvp ? [
                'status' => $guest->rsvp->attendance_status->value,
                'status_label' => $guest->rsvp->status_label,
                'attendance_count' => $guest->rsvp->attendance_count,
                'message' => $guest->rsvp->message,
                'responded_at' => $guest->rsvp->formatted_responded_at,
            ] : null,
        ]);
    }

    /**
     * Admin view: List all RSVPs for an invitation.
     */
    public function index(Request $request, Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $query = $invitation->rsvps()->with('guest');

        // Apply filters
        if ($request->filled('status')) {
            $status = AttendanceStatus::tryFrom($request->input('status'));
            if ($status) {
                $query->withStatus($status);
            }
        }

        if ($request->boolean('responded_only')) {
            $query->responded();
        }

        if ($request->boolean('with_message')) {
            $query->withMessage();
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'responded_at');
        $sortDir = $request->input('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $rsvps = $query->paginate(25)->withQueryString();

        // Get stats
        $stats = $invitation->getRsvpStats();

        return view('rsvp.index', compact('invitation', 'rsvps', 'stats'));
    }

    /**
     * Admin: Add notes to an RSVP.
     */
    public function updateNotes(Request $request, Invitation $invitation, Rsvp $rsvp): RedirectResponse
    {
        $this->authorize('view', $invitation);

        if ($rsvp->invitation_id !== $invitation->id) {
            abort(404);
        }

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $rsvp->update(['admin_notes' => $request->input('admin_notes')]);

        return back()->with('success', 'Notes updated successfully!');
    }
}
