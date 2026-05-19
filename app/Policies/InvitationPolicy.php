<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Authorization policy for Invitation model.
 *
 * Ensures multi-tenant security - users can only access their own invitations.
 * Prevents IDOR vulnerabilities.
 */
class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invitations.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own invitation list
        return true;
    }

    /**
     * Determine whether the user can view the invitation.
     */
    public function view(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can create invitations.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create invitations
        // Could add subscription/plan limits here in the future
        return true;
    }



    /**
     * Determine whether the user can update the invitation.
     */
    public function update(User $user, Invitation $invitation): Response
    {
        if ($user->id !== $invitation->user_id) {
            return Response::deny('You do not own this invitation.');
        }

        if (! $invitation->isEditable()) {
            return Response::deny('This invitation has been archived and cannot be edited.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the invitation.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can restore the invitation.
     */
    public function restore(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can permanently delete the invitation.
     */
    public function forceDelete(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can publish the invitation.
     */
    public function publish(User $user, Invitation $invitation): Response
    {
        if ($user->id !== $invitation->user_id) {
            return Response::deny('You do not own this invitation.');
        }

        if (! $invitation->isEditable()) {
            return Response::deny('This invitation has been archived.');
        }

        return Response::allow();
    }



    /**
     * Determine whether the user can duplicate the invitation.
     */
    public function duplicate(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can view analytics for the invitation.
     */
    public function viewAnalytics(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can manage guests for this invitation.
     */
    public function manageGuests(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    /**
     * Determine whether the user can manage events for this invitation.
     */
    public function manageEvents(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id && $invitation->isEditable();
    }

    /**
     * Determine whether the user can manage gift accounts for this invitation.
     */
    public function manageGiftAccounts(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id && $invitation->isEditable();
    }

    /**
     * Determine whether the user can archive the invitation.
     */
    public function archive(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }
}
