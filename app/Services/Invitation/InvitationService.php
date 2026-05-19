<?php

declare(strict_types=1);

namespace App\Services\Invitation;

use App\Enums\InvitationStatus;
use App\Exceptions\InvitationException;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service class for invitation business logic.
 *
 * Handles all invitation-related operations with proper transactions
 * and ownership validation for multi-tenant security.
 */
class InvitationService
{
    /**
     * Default settings for new invitations.
     */
    private const DEFAULT_SETTINGS = [
        'rsvp_enabled' => true,
        'gift_enabled' => true,
        'guest_book_enabled' => true,
        'countdown_enabled' => true,
        'music_autoplay' => false,
        'show_guest_count' => true,
        'require_attendance_count' => true,
        'max_attendance_per_guest' => 5,
    ];

    /**
     * Get paginated invitations for a user.
     *
     * @param User $user The authenticated user
     * @param int $perPage Items per page
     * @param array<string, mixed> $filters Optional filters
     * @return LengthAwarePaginator<Invitation>
     */
    public function getForUser(
        User $user,
        int $perPage = 10,
        array $filters = []
    ): LengthAwarePaginator {
        $query = $user->invitations()->with(['template', 'events']);

        // Apply status filter
        if (isset($filters['status']) && $filters['status'] instanceof InvitationStatus) {
            $query->status($filters['status']);
        }

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('bride_name', 'like', "%{$search}%")
                    ->orWhere('groom_name', 'like', "%{$search}%");
            });
        }

        // Apply date filter
        if (! empty($filters['upcoming'])) {
            $query->upcoming();
        } elseif (! empty($filters['past'])) {
            $query->past();
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get all invitations for a user (no pagination).
     *
     * @param User $user The authenticated user
     * @param InvitationStatus|null $status Optional status filter
     * @return Collection<int, Invitation>
     */
    public function getAllForUser(User $user, ?InvitationStatus $status = null): Collection
    {
        $query = $user->invitations()->with(['template', 'events']);

        if ($status !== null) {
            $query->status($status);
        }

        return $query->latest()->get();
    }

    /**
     * Get a single invitation by slug with ownership validation.
     *
     * @param User $user The authenticated user
     * @param string $slug The invitation slug
     * @return Invitation
     * @throws InvitationException
     */
    public function getBySlug(User $user, string $slug): Invitation
    {
        $invitation = $user->invitations()
            ->where('slug', $slug)
            ->with(['template', 'events', 'giftAccounts'])
            ->first();

        if (! $invitation) {
            throw InvitationException::notFound();
        }

        return $invitation;
    }

    /**
     * Get a single invitation by ID with ownership validation.
     *
     * @param User $user The authenticated user
     * @param int $id The invitation ID
     * @return Invitation
     * @throws InvitationException
     */
    public function getById(User $user, int $id): Invitation
    {
        $invitation = $user->invitations()
            ->where('id', $id)
            ->with(['template', 'events', 'giftAccounts'])
            ->first();

        if (! $invitation) {
            throw InvitationException::notFound();
        }

        return $invitation;
    }

    /**
     * Create a new invitation with default settings.
     *
     * @param User $user The authenticated user
     * @param array<string, mixed> $data Invitation data
     * @return Invitation
     */
    public function create(User $user, array $data): Invitation
    {
        return DB::transaction(function () use ($user, $data) {
            // Generate unique slug
            $slug = $this->generateUniqueSlug(
                $data['bride_name'] ?? '',
                $data['groom_name'] ?? ''
            );

            // Merge default settings with provided settings
            $settings = array_merge(
                self::DEFAULT_SETTINGS,
                $data['settings'] ?? []
            );

            // Prepare invitation data
            $invitationData = array_merge($data, [
                'user_id' => $user->id,
                'slug' => $slug,
                'settings' => $settings,
                'status' => InvitationStatus::Draft,
            ]);

            // Create the invitation
            $invitation = Invitation::create($invitationData);

            // Create default event if event data is provided
            if (! empty($data['events'])) {
                foreach ($data['events'] as $eventData) {
                    $invitation->events()->create($eventData);
                }
            }

            // Create default gift accounts if provided
            if (! empty($data['gift_accounts'])) {
                foreach ($data['gift_accounts'] as $giftAccountData) {
                    $invitation->giftAccounts()->create($giftAccountData);
                }
            }

            return $invitation->load(['events', 'giftAccounts']);
        });
    }

    /**
     * Update an existing invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to update
     * @param array<string, mixed> $data Updated data
     * @return Invitation
     * @throws InvitationException
     */
    public function update(User $user, Invitation $invitation, array $data): Invitation
    {
        $this->validateOwnership($user, $invitation);

        if (! $invitation->isEditable()) {
            throw InvitationException::archived();
        }

        return DB::transaction(function () use ($invitation, $data) {
            // Handle slug update if names changed
            if (
                (isset($data['bride_name']) && $data['bride_name'] !== $invitation->bride_name) ||
                (isset($data['groom_name']) && $data['groom_name'] !== $invitation->groom_name)
            ) {
                // Only regenerate slug if not manually provided
                if (empty($data['slug'])) {
                    $data['slug'] = $this->generateUniqueSlug(
                        $data['bride_name'] ?? $invitation->bride_name,
                        $data['groom_name'] ?? $invitation->groom_name,
                        $invitation->id
                    );
                }
            }

            // Merge settings
            if (isset($data['settings'])) {
                $data['settings'] = array_merge(
                    $invitation->settings ?? self::DEFAULT_SETTINGS,
                    $data['settings']
                );
            }

            $invitation->update($data);

            return $invitation->fresh(['template', 'events', 'giftAccounts']);
        });
    }

    /**
     * Duplicate an invitation with all related data.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to duplicate
     * @return Invitation
     * @throws InvitationException
     */
    public function duplicate(User $user, Invitation $invitation): Invitation
    {
        $this->validateOwnership($user, $invitation);

        return DB::transaction(function () use ($invitation) {
            // Generate new slug for the duplicate
            $newSlug = $this->generateUniqueSlug(
                $invitation->bride_name,
                $invitation->groom_name
            );

            // Replicate the invitation
            $clone = $invitation->replicate([
                'slug',
                'status',
                'published_at',
                'view_count',
                'unique_visitor_count',
                'created_at',
                'updated_at',
            ]);

            $clone->slug = $newSlug;
            $clone->title = $invitation->title . ' (Copy)';
            $clone->status = InvitationStatus::Draft;
            $clone->published_at = null;
            $clone->view_count = 0;
            $clone->unique_visitor_count = 0;
            $clone->save();

            // Duplicate events
            foreach ($invitation->events as $event) {
                $eventClone = $event->replicate([
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]);
                $eventClone->invitation_id = $clone->id;
                $eventClone->save();
            }

            // Duplicate gift accounts (without usage stats)
            foreach ($invitation->giftAccounts as $giftAccount) {
                $giftAccountClone = $giftAccount->replicate([
                    'view_count',
                    'copy_count',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]);
                $giftAccountClone->invitation_id = $clone->id;
                $giftAccountClone->view_count = 0;
                $giftAccountClone->copy_count = 0;
                $giftAccountClone->save();
            }

            return $clone->load(['events', 'giftAccounts']);
        });
    }

    /**
     * Publish an invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to publish
     * @return Invitation
     * @throws InvitationException
     */
    public function publish(User $user, Invitation $invitation): Invitation
    {
        $this->validateOwnership($user, $invitation);

        if ($invitation->status === InvitationStatus::Archived) {
            throw InvitationException::archived();
        }

        return DB::transaction(function () use ($invitation) {
            $invitation->update([
                'status' => InvitationStatus::Published,
                'published_at' => $invitation->published_at ?? now(),
            ]);

            return $invitation->fresh();
        });
    }

    /**
     * Unpublish an invitation (set back to draft).
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to unpublish
     * @return Invitation
     * @throws InvitationException
     */
    public function unpublish(User $user, Invitation $invitation): Invitation
    {
        $this->validateOwnership($user, $invitation);

        if ($invitation->status === InvitationStatus::Archived) {
            throw InvitationException::archived();
        }

        return DB::transaction(function () use ($invitation) {
            $invitation->update([
                'status' => InvitationStatus::Draft,
            ]);

            return $invitation->fresh();
        });
    }

    /**
     * Archive an invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to archive
     * @return Invitation
     * @throws InvitationException
     */
    public function archive(User $user, Invitation $invitation): Invitation
    {
        $this->validateOwnership($user, $invitation);

        return DB::transaction(function () use ($invitation) {
            $invitation->update([
                'status' => InvitationStatus::Archived,
            ]);

            return $invitation->fresh();
        });
    }

    /**
     * Soft delete an invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to delete
     * @return bool
     * @throws InvitationException
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        $this->validateOwnership($user, $invitation);

        return DB::transaction(function () use ($invitation) {
            // Soft delete related data
            $invitation->events()->delete();
            $invitation->guests()->delete();
            $invitation->giftAccounts()->delete();

            return $invitation->delete();
        });
    }

    /**
     * Restore a soft-deleted invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to restore
     * @return Invitation
     * @throws InvitationException
     */
    public function restore(User $user, Invitation $invitation): Invitation
    {
        $this->validateOwnership($user, $invitation);

        return DB::transaction(function () use ($invitation) {
            // Restore the invitation
            $invitation->restore();

            // Restore related data
            $invitation->events()->withTrashed()->restore();
            $invitation->guests()->withTrashed()->restore();
            $invitation->giftAccounts()->withTrashed()->restore();

            return $invitation->fresh(['events', 'guests', 'giftAccounts']);
        });
    }

    /**
     * Permanently delete an invitation.
     *
     * @param User $user The authenticated user
     * @param Invitation $invitation The invitation to permanently delete
     * @return bool
     * @throws InvitationException
     */
    public function forceDelete(User $user, Invitation $invitation): bool
    {
        $this->validateOwnership($user, $invitation);

        return DB::transaction(function () use ($invitation) {
            // Force delete related data
            $invitation->events()->withTrashed()->forceDelete();
            $invitation->guests()->withTrashed()->forceDelete();
            $invitation->rsvps()->delete();
            $invitation->giftAccounts()->withTrashed()->forceDelete();
            $invitation->analytics()->delete();

            return $invitation->forceDelete();
        });
    }

    /**
     * Generate a unique slug for an invitation.
     *
     * @param string $brideName Bride's name
     * @param string $groomName Groom's name
     * @param int|null $excludeId Invitation ID to exclude from uniqueness check
     * @return string
     */
    public function generateUniqueSlug(
        string $brideName,
        string $groomName,
        ?int $excludeId = null
    ): string {
        $baseSlug = Str::slug($brideName . ' ' . $groomName);

        if (empty($baseSlug)) {
            $baseSlug = 'invitation-' . Str::random(8);
        }

        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists.
     *
     * @param string $slug The slug to check
     * @param int|null $excludeId Invitation ID to exclude
     * @return bool
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Invitation::withTrashed()->where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Validate that the user owns the invitation.
     *
     * @param User $user The user to validate
     * @param Invitation $invitation The invitation to check
     * @throws InvitationException
     */
    public function validateOwnership(User $user, Invitation $invitation): void
    {
        if ($user->id !== $invitation->user_id) {
            throw InvitationException::unauthorized();
        }
    }

    /**
     * Get invitation statistics for a user.
     *
     * @param User $user The authenticated user
     * @return array<string, int>
     */
    public function getStatistics(User $user): array
    {
        $invitations = $user->invitations();

        return [
            'total' => $invitations->count(),
            'draft' => $invitations->clone()->draft()->count(),
            'published' => $invitations->clone()->published()->count(),
            'archived' => $invitations->clone()->archived()->count(),
            'total_views' => $invitations->clone()->sum('view_count'),
            'total_unique_visitors' => $invitations->clone()->sum('unique_visitor_count'),
        ];
    }

    /**
     * Get public invitation by slug (for guest viewing).
     *
     * @param string $slug The invitation slug
     * @return Invitation
     * @throws InvitationException
     */
    public function getPublicBySlug(string $slug): Invitation
    {
        $invitation = Invitation::where('slug', $slug)
            ->with(['template', 'events', 'giftAccounts'])
            ->first();

        if (! $invitation) {
            throw InvitationException::invalidSlug();
        }

        if (! $invitation->isPublic()) {
            throw InvitationException::notPublished();
        }

        return $invitation;
    }
}
