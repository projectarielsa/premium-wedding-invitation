<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Authorization policy for Guest model.
 *
 * Guests are accessed through their invitation, so we verify
 * the user owns the invitation that the guest belongs to.
 * 
 * Admin and super_admin users bypass all policy restrictions.
 */
class GuestPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin users bypass all policy restrictions.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // Fall through to specific policy methods
    }

    /**
     * Determine whether the user can view any guests for an invitation.
     */
    public function viewAny(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can view the guest.
     */
    public function view(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can create guests for an invitation.
     */
    public function create(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }



    /**
     * Determine whether the user can update the guest.
     */
    public function update(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can delete the guest.
     */
    public function delete(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can restore the guest.
     */
    public function restore(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can permanently delete the guest.
     */
    public function forceDelete(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can import guests for an invitation.
     */
    public function import(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can bulk manage guests for an invitation.
     */
    public function bulkManage(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }



    /**
     * Determine whether the user can send WhatsApp to this guest.
     */
    public function sendWhatsapp(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can check in this guest.
     */
    public function checkIn(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can view the guest's RSVP.
     */
    public function viewRsvp(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }

    /**
     * Determine whether the user can generate QR code for this guest.
     */
    public function generateQrCode(User $user, Guest $guest): bool
    {
        return $user->id === $guest->invitation->user_id;
    }
}
