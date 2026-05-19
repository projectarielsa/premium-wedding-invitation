<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGiftAccountRequest;
use App\Http\Requests\UpdateGiftAccountRequest;
use App\Models\GiftAccount;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for managing gift accounts within an invitation.
 *
 * Handles digital gift account CRUD operations including bank transfers,
 * e-wallets, and QRIS payment options with QR code support.
 */
class GiftAccountController extends Controller
{
    /**
     * Store a newly created gift account.
     */
    public function store(StoreGiftAccountRequest $request, Invitation $invitation): RedirectResponse
    {
        $data = $request->validated();

        // Handle provider logo upload
        if ($request->hasFile('provider_logo')) {
            $data['provider_logo'] = $request->file('provider_logo')
                ->store('gift-accounts/logos', 'public');
        }

        // Handle QR image upload
        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')
                ->store('gift-accounts/qr', 'public');
        }

        // Set the next sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $invitation->giftAccounts()->max('sort_order') + 1;
        }

        $invitation->giftAccounts()->create($data);

        return back()->with('success', 'Gift account added successfully!');
    }



    /**
     * Update the specified gift account.
     */
    public function update(
        UpdateGiftAccountRequest $request,
        Invitation $invitation,
        GiftAccount $giftAccount
    ): RedirectResponse {
        // Verify gift account belongs to invitation
        if ($giftAccount->invitation_id !== $invitation->id) {
            abort(404);
        }

        $data = $request->validated();

        // Handle provider logo upload/removal
        if ($request->hasFile('provider_logo')) {
            if ($giftAccount->provider_logo) {
                Storage::disk('public')->delete($giftAccount->provider_logo);
            }
            $data['provider_logo'] = $request->file('provider_logo')
                ->store('gift-accounts/logos', 'public');
        } elseif ($request->boolean('remove_provider_logo') && $giftAccount->provider_logo) {
            Storage::disk('public')->delete($giftAccount->provider_logo);
            $data['provider_logo'] = null;
        }

        // Handle QR image upload/removal
        if ($request->hasFile('qr_image')) {
            if ($giftAccount->qr_image) {
                Storage::disk('public')->delete($giftAccount->qr_image);
            }
            $data['qr_image'] = $request->file('qr_image')
                ->store('gift-accounts/qr', 'public');
        } elseif ($request->boolean('remove_qr_image') && $giftAccount->qr_image) {
            Storage::disk('public')->delete($giftAccount->qr_image);
            $data['qr_image'] = null;
        }

        $giftAccount->update($data);

        return back()->with('success', 'Gift account updated successfully!');
    }

    /**
     * Remove the specified gift account.
     */
    public function destroy(
        Request $request,
        Invitation $invitation,
        GiftAccount $giftAccount
    ): RedirectResponse {
        $this->authorize('manageGiftAccounts', $invitation);

        // Verify gift account belongs to invitation
        if ($giftAccount->invitation_id !== $invitation->id) {
            abort(404);
        }

        // Delete associated files
        if ($giftAccount->provider_logo) {
            Storage::disk('public')->delete($giftAccount->provider_logo);
        }
        if ($giftAccount->qr_image) {
            Storage::disk('public')->delete($giftAccount->qr_image);
        }

        $giftAccount->delete();

        return back()->with('success', 'Gift account removed successfully!');
    }



    /**
     * Reorder gift accounts within an invitation.
     */
    public function reorder(Request $request, Invitation $invitation): JsonResponse
    {
        $this->authorize('manageGiftAccounts', $invitation);

        $request->validate([
            'accounts' => ['required', 'array'],
            'accounts.*' => ['required', 'integer', 'exists:gift_accounts,id'],
        ]);

        DB::transaction(function () use ($request, $invitation) {
            foreach ($request->input('accounts') as $index => $accountId) {
                $invitation->giftAccounts()
                    ->where('id', $accountId)
                    ->update(['sort_order' => $index]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Gift accounts reordered successfully!',
        ]);
    }

    /**
     * Toggle gift account active status.
     */
    public function toggleActive(
        Request $request,
        Invitation $invitation,
        GiftAccount $giftAccount
    ): RedirectResponse {
        $this->authorize('manageGiftAccounts', $invitation);

        // Verify gift account belongs to invitation
        if ($giftAccount->invitation_id !== $invitation->id) {
            abort(404);
        }

        $giftAccount->update(['is_active' => !$giftAccount->is_active]);

        $status = $giftAccount->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Gift account {$status} successfully!");
    }

    /**
     * Track account number copy action (public endpoint).
     */
    public function trackCopy(Request $request, string $slug, GiftAccount $giftAccount): JsonResponse
    {
        // Verify gift account belongs to this invitation slug
        if ($giftAccount->invitation->slug !== $slug) {
            abort(404);
        }

        $giftAccount->incrementCopyCount();

        return response()->json(['success' => true]);
    }

    /**
     * Track gift section view (public endpoint).
     */
    public function trackView(Request $request, string $slug, GiftAccount $giftAccount): JsonResponse
    {
        // Verify gift account belongs to this invitation slug
        if ($giftAccount->invitation->slug !== $slug) {
            abort(404);
        }

        $giftAccount->incrementViewCount();

        return response()->json(['success' => true]);
    }
}
