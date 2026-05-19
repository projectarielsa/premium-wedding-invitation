<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\InvitationStatus;
use App\Exceptions\InvitationException;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Invitation;
use App\Models\Template;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for managing wedding invitations.
 *
 * Handles all invitation CRUD operations, publishing, duplicating,
 * and previewing. Uses InvitationService for business logic.
 */
class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {}

    /**
     * Display a listing of the user's invitations.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Invitation::class);

        $filters = [
            'status' => $request->filled('status') 
                ? InvitationStatus::tryFrom($request->input('status')) 
                : null,
            'search' => $request->input('search'),
            'upcoming' => $request->boolean('upcoming'),
            'past' => $request->boolean('past'),
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_direction' => $request->input('sort_direction', 'desc'),
        ];

        $invitations = $this->invitationService->getForUser(
            $request->user(),
            perPage: 12,
            filters: array_filter($filters)
        );

        $stats = $this->invitationService->getStatistics($request->user());

        return view('invitations.index', compact('invitations', 'stats', 'filters'));
    }



    /**
     * Show the form for creating a new invitation.
     */
    public function create(): View
    {
        $this->authorize('create', Invitation::class);

        $templates = Template::active()->ordered()->get();

        return view('invitations.create', compact('templates'));
    }

    /**
     * Store a newly created invitation.
     */
    public function store(StoreInvitationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('invitations/covers', 'public');
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('invitations/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        // Handle SEO image upload
        if ($request->hasFile('seo_image')) {
            $data['seo_image'] = $request->file('seo_image')
                ->store('invitations/seo', 'public');
        }

        $invitation = $this->invitationService->create($request->user(), $data);

        return redirect()
            ->route('invitations.edit', $invitation)
            ->with('success', 'Invitation created successfully! You can now customize it.');
    }

    /**
     * Display the specified invitation.
     */
    public function show(Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $invitation->load(['events', 'guests', 'giftAccounts', 'template']);

        $rsvpStats = $invitation->getRsvpStats();

        return view('invitations.show', compact('invitation', 'rsvpStats'));
    }



    /**
     * Show the form for editing the invitation.
     */
    public function edit(Invitation $invitation): View
    {
        $this->authorize('update', $invitation);

        $invitation->load(['events', 'giftAccounts', 'template']);
        $templates = Template::active()->ordered()->get();

        return view('invitations.edit', compact('invitation', 'templates'));
    }

    /**
     * Update the specified invitation.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation): RedirectResponse
    {
        $data = $request->validated();

        // Handle cover image upload/removal
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($invitation->cover_image) {
                Storage::disk('public')->delete($invitation->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('invitations/covers', 'public');
        } elseif ($request->boolean('remove_cover_image') && $invitation->cover_image) {
            Storage::disk('public')->delete($invitation->cover_image);
            $data['cover_image'] = null;
        }

        // Handle gallery uploads/removals
        if ($request->has('remove_gallery_images')) {
            $currentGallery = $invitation->gallery ?? [];
            foreach ($request->input('remove_gallery_images', []) as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $currentGallery = array_filter($currentGallery, fn($img) => $img !== $imagePath);
            }
            $data['gallery'] = array_values($currentGallery);
        }

        if ($request->hasFile('gallery')) {
            $gallery = $data['gallery'] ?? $invitation->gallery ?? [];
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('invitations/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        // Handle SEO image upload
        if ($request->hasFile('seo_image')) {
            if ($invitation->seo_image) {
                Storage::disk('public')->delete($invitation->seo_image);
            }
            $data['seo_image'] = $request->file('seo_image')
                ->store('invitations/seo', 'public');
        }

        try {
            $this->invitationService->update($request->user(), $invitation, $data);

            return redirect()
                ->route('invitations.edit', $invitation)
                ->with('success', 'Invitation updated successfully!');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }



    /**
     * Duplicate the specified invitation.
     */
    public function duplicate(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('duplicate', $invitation);

        try {
            $clone = $this->invitationService->duplicate($request->user(), $invitation);

            return redirect()
                ->route('invitations.edit', $clone)
                ->with('success', 'Invitation duplicated successfully!');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Publish the specified invitation.
     */
    public function publish(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('publish', $invitation);

        try {
            $this->invitationService->publish($request->user(), $invitation);

            return back()->with('success', 'Invitation published successfully! It is now publicly accessible.');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Unpublish the specified invitation.
     */
    public function unpublish(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('publish', $invitation);

        try {
            $this->invitationService->unpublish($request->user(), $invitation);

            return back()->with('success', 'Invitation unpublished. It is now a draft.');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Soft delete the specified invitation.
     */
    public function destroy(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        try {
            $this->invitationService->delete($request->user(), $invitation);

            return redirect()
                ->route('invitations.index')
                ->with('success', 'Invitation deleted successfully.');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }



    /**
     * Restore a soft-deleted invitation.
     */
    public function restore(Request $request, int $id): RedirectResponse
    {
        $invitation = Invitation::withTrashed()->findOrFail($id);

        $this->authorize('restore', $invitation);

        try {
            $this->invitationService->restore($request->user(), $invitation);

            return redirect()
                ->route('invitations.show', $invitation)
                ->with('success', 'Invitation restored successfully.');
        } catch (InvitationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Preview the invitation (owner preview mode).
     */
    public function preview(Invitation $invitation): View
    {
        $this->authorize('view', $invitation);

        $invitation->load(['events', 'giftAccounts', 'template']);

        return view('invitations.preview', [
            'invitation' => $invitation,
            'isPreview' => true,
            'guest' => null,
        ]);
    }

    /**
     * Public invitation view (for guests).
     */
    public function publicShow(string $slug): View
    {
        try {
            $invitation = $this->invitationService->getPublicBySlug($slug);

            // Increment view count (tracking handled by middleware)
            $invitation->incrementViewCount();

            return view('invitations.public', [
                'invitation' => $invitation,
                'isPreview' => false,
                'guest' => null,
            ]);
        } catch (InvitationException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Public invitation view with guest personalization.
     */
    public function publicShowWithGuest(string $slug, string $guestToken): View
    {
        try {
            $invitation = $this->invitationService->getPublicBySlug($slug);

            $guest = $invitation->guests()
                ->where('slug_token', $guestToken)
                ->first();

            if ($guest) {
                $guest->recordVisit();
            }

            $invitation->incrementViewCount();

            return view('invitations.public', [
                'invitation' => $invitation,
                'isPreview' => false,
                'guest' => $guest,
            ]);
        } catch (InvitationException $e) {
            abort(404, $e->getMessage());
        }
    }
}
