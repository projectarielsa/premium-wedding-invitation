<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin users bypass all policy restrictions except user-specific actions.
     */
    public function before(User $user, string $ability): ?bool
    {
        // For admin-only actions, admins always pass
        if ($user->isAdmin() && in_array($ability, ['approve', 'reject', 'delete', 'viewAny'])) {
            return true;
        }

        return null; // Fall through to specific policy methods
    }

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // Admins can view all orders
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only view their own orders
        return $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create orders
        return true;
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        // Admins can update any order
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only update their own orders (e.g., upload payment proof)
        return $order->user_id === $user->id && !$order->isFinal();
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only admins can delete orders
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve the order.
     */
    public function approve(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the order.
     */
    public function reject(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can upload payment proof.
     */
    public function uploadPayment(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->canUploadPaymentProof();
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Users can cancel their own orders if status allows
        if ($order->user_id === $user->id && $order->status->canBeCancelled()) {
            return true;
        }

        // Admins can cancel any order
        return $user->isAdmin();
    }
}
