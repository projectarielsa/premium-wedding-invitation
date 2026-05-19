<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller for managing wedding events within an invitation.
 *
 * Events are nested resources under invitations and include
 * ceremonies, receptions, and other wedding-related gatherings.
 */
class EventController extends Controller
{
    /**
     * Store a newly created event for the invitation.
     */
    public function store(StoreEventRequest $request, Invitation $invitation): RedirectResponse
    {
        $data = $request->validated();

        // Set the next sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $invitation->events()->max('sort_order') + 1;
        }

        $invitation->events()->create($data);

        return back()->with('success', 'Event added successfully!');
    }

    /**
     * Update the specified event.
     */
    public function update(
        UpdateEventRequest $request,
        Invitation $invitation,
        Event $event
    ): RedirectResponse {
        // Verify event belongs to invitation
        if ($event->invitation_id !== $invitation->id) {
            abort(404);
        }

        $event->update($request->validated());

        return back()->with('success', 'Event updated successfully!');
    }



    /**
     * Remove the specified event.
     */
    public function destroy(
        Request $request,
        Invitation $invitation,
        Event $event
    ): RedirectResponse {
        $this->authorize('manageEvents', $invitation);

        // Verify event belongs to invitation
        if ($event->invitation_id !== $invitation->id) {
            abort(404);
        }

        $event->delete();

        return back()->with('success', 'Event removed successfully!');
    }

    /**
     * Reorder events within an invitation.
     */
    public function reorder(Request $request, Invitation $invitation): JsonResponse
    {
        $this->authorize('manageEvents', $invitation);

        $request->validate([
            'events' => ['required', 'array'],
            'events.*' => ['required', 'integer', 'exists:events,id'],
        ]);

        DB::transaction(function () use ($request, $invitation) {
            foreach ($request->input('events') as $index => $eventId) {
                $invitation->events()
                    ->where('id', $eventId)
                    ->update(['sort_order' => $index]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Events reordered successfully!',
        ]);
    }

    /**
     * Toggle event active status.
     */
    public function toggleActive(
        Request $request,
        Invitation $invitation,
        Event $event
    ): RedirectResponse {
        $this->authorize('manageEvents', $invitation);

        // Verify event belongs to invitation
        if ($event->invitation_id !== $invitation->id) {
            abort(404);
        }

        $event->update(['is_active' => !$event->is_active]);

        $status = $event->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Event {$status} successfully!");
    }
}
