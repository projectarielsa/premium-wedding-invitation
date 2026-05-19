<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::with('activePackage');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by package
        if ($request->filled('package_id')) {
            if ($request->package_id === 'none') {
                $query->whereNull('active_package_id');
            } else {
                $query->where('active_package_id', $request->package_id);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->status === 'active') {
                $query->where('is_suspended', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $packages = Package::active()->ordered()->get();

        // Statistics
        $stats = [
            'total' => User::count(),
            'customers' => User::where('role', UserRole::Customer)->count(),
            'admins' => User::whereIn('role', [UserRole::Admin, UserRole::SuperAdmin])->count(),
            'suspended' => User::where('is_suspended', true)->count(),
            'with_package' => User::whereNotNull('active_package_id')->count(),
        ];

        return view('admin.users.index', compact('users', 'packages', 'stats'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load(['activePackage', 'invitations', 'orders.package']);

        $stats = $user->getStats();

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $packages = Package::active()->ordered()->get();

        return view('admin.users.edit', compact('user', 'packages'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:customer,admin,super_admin',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        // Only super_admin can change roles
        if (!$request->user()->isSuperAdmin() && $validated['role'] !== $user->role->value) {
            return back()->with('error', 'Only super admin can change user roles.');
        }

        // Prevent demoting yourself
        if ($user->id === $request->user()->id && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Suspend a user.
     */
    public function suspend(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        // Cannot suspend yourself
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot suspend yourself.');
        }

        // Cannot suspend super admin (unless you're a super admin)
        if ($user->isSuperAdmin() && !$request->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot suspend a super admin.');
        }

        $user->update([
            'is_suspended' => true,
            'suspension_reason' => $request->suspension_reason,
        ]);

        return back()->with('success', 'User has been suspended.');
    }

    /**
     * Unsuspend a user.
     */
    public function unsuspend(User $user): RedirectResponse
    {
        $user->update([
            'is_suspended' => false,
            'suspension_reason' => null,
        ]);

        return back()->with('success', 'User has been unsuspended.');
    }

    /**
     * Manually assign a package to user.
     */
    public function assignPackage(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'duration_days' => 'nullable|integer|min:1',
        ]);

        $package = Package::findOrFail($request->package_id);
        $durationDays = $request->duration_days ?? $package->duration_days;

        $startsAt = now();
        $expiresAt = $startsAt->copy()->addDays($durationDays);

        $user->update([
            'active_package_id' => $package->id,
            'package_started_at' => $startsAt,
            'package_expires_at' => $expiresAt,
        ]);

        return back()->with('success', "Package '{$package->name}' assigned to user for {$durationDays} days.");
    }

    /**
     * Remove package from user.
     */
    public function removePackage(User $user): RedirectResponse
    {
        $user->deactivatePackage();

        return back()->with('success', 'Package removed from user.');
    }

    /**
     * Extend user's package.
     */
    public function extendPackage(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        if (!$user->active_package_id) {
            return back()->with('error', 'User does not have an active package.');
        }

        $currentExpiry = $user->package_expires_at ?? now();
        $newExpiry = $currentExpiry->copy()->addDays($request->days);

        $user->update(['package_expires_at' => $newExpiry]);

        return back()->with('success', "Package extended by {$request->days} days. New expiry: {$newExpiry->format('d M Y')}");
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        $query = User::with('activePackage');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();

        $filename = 'users_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Role',
                'Package',
                'Package Expires',
                'Status',
                'Created At',
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role->label(),
                    $user->activePackage?->name ?? 'None',
                    $user->package_expires_at?->format('Y-m-d'),
                    $user->is_suspended ? 'Suspended' : 'Active',
                    $user->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
