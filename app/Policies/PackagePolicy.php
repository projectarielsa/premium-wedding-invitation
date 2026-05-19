<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin users bypass all policy restrictions for management.
     */
    public function before(User $user, string $ability): ?bool
    {
        // Admins can do anything with packages
        if ($user->isAdmin() && in_array($ability, ['create', 'update', 'view'])) {
            return true;
        }
        
        // Super admin can delete
        if ($user->isSuperAdmin() && $ability === 'delete') {
            return true;
        }

        return null; // Fall through to specific policy methods
    }

    /**
     * Determine whether the user can view any packages.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view packages (pricing page)
        return true;
    }

    /**
     * Determine whether the user can view the package.
     */
    public function view(?User $user, Package $package): bool
    {
        // Anyone can view active packages
        // Only admins can view inactive packages
        if ($package->is_active) {
            return true;
        }

        return $user?->isAdmin() ?? false;
    }

    /**
     * Determine whether the user can create packages.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the package.
     */
    public function update(User $user, Package $package): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the package.
     */
    public function delete(User $user, Package $package): bool
    {
        return $user->isSuperAdmin();
    }
}
