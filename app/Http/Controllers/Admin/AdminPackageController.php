<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index(): View
    {
        $packages = Package::withCount('users')
            ->ordered()
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create(): View
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:packages,slug',
            'description' => 'nullable|string|max:1000',
            'badge' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_invitations' => 'required|integer|min:1',
            'max_guests_per_invitation' => 'required|integer|min:1',
            'max_events_per_invitation' => 'required|integer|min:1',
            'max_gift_accounts' => 'required|integer|min:0',
            'max_gallery_images' => 'required|integer|min:0',
            'rsvp_enabled' => 'boolean',
            'gift_enabled' => 'boolean',
            'qr_checkin_enabled' => 'boolean',
            'analytics_enabled' => 'boolean',
            'custom_music_enabled' => 'boolean',
            'custom_domain_enabled' => 'boolean',
            'export_enabled' => 'boolean',
            'whatsapp_blast_enabled' => 'boolean',
            'guest_book_enabled' => 'boolean',
            'countdown_enabled' => 'boolean',
            'story_section_enabled' => 'boolean',
            'remove_watermark' => 'boolean',
            'template_access' => 'nullable|array',
            'support_level' => 'required|in:community,email,priority,dedicated',
            'support_response_hours' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'features_list' => 'nullable|array',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle boolean fields
        $booleanFields = [
            'rsvp_enabled', 'gift_enabled', 'qr_checkin_enabled', 'analytics_enabled',
            'custom_music_enabled', 'custom_domain_enabled', 'export_enabled',
            'whatsapp_blast_enabled', 'guest_book_enabled', 'countdown_enabled',
            'story_section_enabled', 'remove_watermark', 'is_active', 'is_featured'
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        Package::create($validated);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package): View
    {
        $package->loadCount('users');
        
        $activeUsers = $package->users()
            ->where(function ($q) {
                $q->whereNull('package_expires_at')
                  ->orWhere('package_expires_at', '>', now());
            })
            ->latest()
            ->take(10)
            ->get();

        return view('admin.packages.show', compact('package', 'activeUsers'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package): View
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:packages,slug,' . $package->id,
            'description' => 'nullable|string|max:1000',
            'badge' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_invitations' => 'required|integer|min:1',
            'max_guests_per_invitation' => 'required|integer|min:1',
            'max_events_per_invitation' => 'required|integer|min:1',
            'max_gift_accounts' => 'required|integer|min:0',
            'max_gallery_images' => 'required|integer|min:0',
            'rsvp_enabled' => 'boolean',
            'gift_enabled' => 'boolean',
            'qr_checkin_enabled' => 'boolean',
            'analytics_enabled' => 'boolean',
            'custom_music_enabled' => 'boolean',
            'custom_domain_enabled' => 'boolean',
            'export_enabled' => 'boolean',
            'whatsapp_blast_enabled' => 'boolean',
            'guest_book_enabled' => 'boolean',
            'countdown_enabled' => 'boolean',
            'story_section_enabled' => 'boolean',
            'remove_watermark' => 'boolean',
            'template_access' => 'nullable|array',
            'support_level' => 'required|in:community,email,priority,dedicated',
            'support_response_hours' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'features_list' => 'nullable|array',
        ]);

        // Handle boolean fields
        $booleanFields = [
            'rsvp_enabled', 'gift_enabled', 'qr_checkin_enabled', 'analytics_enabled',
            'custom_music_enabled', 'custom_domain_enabled', 'export_enabled',
            'whatsapp_blast_enabled', 'guest_book_enabled', 'countdown_enabled',
            'story_section_enabled', 'remove_watermark', 'is_active', 'is_featured'
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        $package->update($validated);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package): RedirectResponse
    {
        // Check if package has active users
        $activeUsersCount = $package->users()
            ->where(function ($q) {
                $q->whereNull('package_expires_at')
                  ->orWhere('package_expires_at', '>', now());
            })
            ->count();

        if ($activeUsersCount > 0) {
            return back()->with('error', "Cannot delete package. There are {$activeUsersCount} active users with this package.");
        }

        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    /**
     * Toggle package active status.
     */
    public function toggleActive(Package $package): RedirectResponse
    {
        $package->update(['is_active' => !$package->is_active]);

        $status = $package->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Package {$status} successfully.");
    }
}
