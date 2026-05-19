<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for guest check-in functionality.
 * Handles QR code scanning and manual check-in at events.
 */
class CheckInController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Display check-in dashboard for an invitation.
     */
    public function index(Request $request, Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $query = $invitation->guests()->with('rsvp');

        // Filter by check-in status
        if ($request->filled('status')) {
            match ($request->input('status')) {
                'checked_in' => $query->checkedIn(),
                'pending' => $query->whereNull('checked_in_at'),
                default => null,
            };
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug_token', 'like', "%{$search}%");
            });
        }

        $guests = $query->orderBy('name')->paginate(50)->withQueryString();

        // Get check-in stats
        $stats = [
            'total' => $invitation->guests()->count(),
            'checked_in' => $invitation->guests()->checkedIn()->count(),
            'expected_attending' => $invitation->rsvps()
                ->where('attendance_status', 'attending')
                ->sum('attendance_count'),
        ];

        return view('checkin.index', compact('invitation', 'guests', 'stats'));
    }

    /**
     * Process check-in via QR code token.
     */
    public function process(Request $request, string $token): JsonResponse|RedirectResponse
    {
        $guest = Guest::where('slug_token', $token)->first();

        if (!$guest) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guest not found.',
                ], 404);
            }
            return back()->with('error', 'Guest not found.');
        }

        if ($guest->is_checked_in) {
            $data = [
                'success' => false,
                'message' => 'Guest already checked in.',
                'guest' => [
                    'name' => $guest->name,
                    'checked_in_at' => $guest->checked_in_at->format('H:i'),
                    'checked_in_by' => $guest->checked_in_by,
                ],
            ];

            if ($request->wantsJson()) {
                return response()->json($data, 422);
            }
            return back()->with('warning', "Guest {$guest->name} was already checked in at {$guest->checked_in_at->format('H:i')}.");
        }

        // Perform check-in
        $staffName = $request->input('staff_name', auth()->user()?->name ?? 'System');
        $guest->checkIn($staffName);

        $data = [
            'success' => true,
            'message' => 'Check-in successful!',
            'guest' => [
                'name' => $guest->name,
                'category' => $guest->category_label,
                'attendance_count' => $guest->rsvp?->attendance_count ?? 1,
                'checked_in_at' => $guest->checked_in_at->format('H:i'),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return back()->with('success', "Successfully checked in: {$guest->name}");
    }

    /**
     * Manual check-in for a guest.
     */
    public function checkIn(Request $request, Invitation $invitation, Guest $guest): RedirectResponse
    {
        $this->authorize('update', $invitation);

        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $staffName = $request->input('staff_name', auth()->user()?->name ?? 'Manual');
        $guest->checkIn($staffName);

        return back()->with('success', "Checked in: {$guest->name}");
    }

    /**
     * Undo check-in for a guest.
     */
    public function undoCheckIn(Request $request, Invitation $invitation, Guest $guest): RedirectResponse
    {
        $this->authorize('update', $invitation);

        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $guest->undoCheckIn();

        return back()->with('success', "Check-in undone for: {$guest->name}");
    }

    /**
     * Generate QR code for a single guest.
     */
    public function generateQr(Invitation $invitation, Guest $guest): JsonResponse
    {
        $this->authorize('update', $invitation);

        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $path = $this->qrCodeService->generateForGuest($guest);

        return response()->json([
            'success' => true,
            'qr_code_url' => asset('storage/' . $path),
            'guest_name' => $guest->name,
        ]);
    }

    /**
     * Bulk generate QR codes for all guests.
     */
    public function bulkGenerateQr(Invitation $invitation): JsonResponse
    {
        $this->authorize('update', $invitation);

        $results = $this->qrCodeService->bulkGenerateForInvitation($invitation);

        return response()->json([
            'success' => true,
            'message' => "Generated {$results['success']} QR codes. {$results['failed']} failed.",
            'results' => $results,
        ]);
    }

    /**
     * Download QR code for a guest.
     */
    public function downloadQr(Invitation $invitation, Guest $guest): StreamedResponse
    {
        $this->authorize('view', $invitation);

        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        // Generate inline for download
        $dataUri = $this->qrCodeService->getQrCodeDataUri($guest);
        $svg = base64_decode(str_replace('data:image/svg+xml;base64,', '', $dataUri));

        $filename = "qr-{$guest->name}-{$guest->slug_token}.svg";

        return response()->streamDownload(function () use ($svg) {
            echo $svg;
        }, $filename, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    /**
     * Get QR code as data URI for display.
     */
    public function getQrDataUri(Invitation $invitation, Guest $guest): JsonResponse
    {
        $this->authorize('view', $invitation);

        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $dataUri = $this->qrCodeService->getQrCodeDataUri($guest);

        return response()->json([
            'success' => true,
            'data_uri' => $dataUri,
            'guest_name' => $guest->name,
        ]);
    }

    /**
     * Bulk check-in guests.
     */
    public function bulkCheckIn(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'guest_ids' => ['required', 'array', 'min:1'],
            'guest_ids.*' => ['required', 'integer'],
        ]);

        $staffName = auth()->user()?->name ?? 'Bulk';

        $checkedIn = $invitation->guests()
            ->whereIn('id', $request->input('guest_ids'))
            ->whereNull('checked_in_at')
            ->update([
                'checked_in_at' => now(),
                'checked_in_by' => $staffName,
            ]);

        return back()->with('success', "{$checkedIn} guests checked in successfully!");
    }

    /**
     * Get check-in statistics as JSON.
     */
    public function stats(Invitation $invitation): JsonResponse
    {
        $this->authorize('view', $invitation);

        $total = $invitation->guests()->count();
        $checkedIn = $invitation->guests()->checkedIn()->count();
        $attending = $invitation->rsvps()
            ->where('attendance_status', 'attending')
            ->count();
        $totalExpected = $invitation->rsvps()
            ->where('attendance_status', 'attending')
            ->sum('attendance_count');

        return response()->json([
            'total_guests' => $total,
            'checked_in' => $checkedIn,
            'pending' => $total - $checkedIn,
            'check_in_rate' => $total > 0 ? round(($checkedIn / $total) * 100, 1) : 0,
            'rsvp_attending' => $attending,
            'total_expected' => $totalExpected,
        ]);
    }
}
