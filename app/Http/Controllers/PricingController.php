<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display the pricing page.
     */
    public function index(): View
    {
        $packages = Package::publicDisplay()->get();

        // Find featured package
        $featuredPackage = $packages->firstWhere('is_featured', true);

        return view('pricing.index', compact('packages', 'featuredPackage'));
    }

    /**
     * Show package details.
     */
    public function show(Package $package): View
    {
        if (!$package->is_active) {
            abort(404);
        }

        // Get all packages for comparison
        $allPackages = Package::publicDisplay()->get();

        return view('pricing.show', compact('package', 'allPackages'));
    }

    /**
     * Compare packages.
     */
    public function compare(): View
    {
        $packages = Package::publicDisplay()->get();

        // Define feature groups for comparison table
        $featureGroups = [
            'Limits' => [
                ['key' => 'max_invitations', 'label' => 'Jumlah Undangan', 'type' => 'limit'],
                ['key' => 'max_guests_per_invitation', 'label' => 'Tamu per Undangan', 'type' => 'limit'],
                ['key' => 'max_events_per_invitation', 'label' => 'Jumlah Acara', 'type' => 'limit'],
                ['key' => 'max_gift_accounts', 'label' => 'Rekening Gift', 'type' => 'limit'],
                ['key' => 'max_gallery_images', 'label' => 'Foto Galeri', 'type' => 'limit'],
            ],
            'Fitur Utama' => [
                ['key' => 'rsvp_enabled', 'label' => 'RSVP Online', 'type' => 'boolean'],
                ['key' => 'gift_enabled', 'label' => 'Amplop Digital', 'type' => 'boolean'],
                ['key' => 'qr_checkin_enabled', 'label' => 'QR Code Check-in', 'type' => 'boolean'],
                ['key' => 'analytics_enabled', 'label' => 'Statistik & Analitik', 'type' => 'boolean'],
                ['key' => 'export_enabled', 'label' => 'Export Data', 'type' => 'boolean'],
                ['key' => 'whatsapp_blast_enabled', 'label' => 'WhatsApp Blast', 'type' => 'boolean'],
            ],
            'Kustomisasi' => [
                ['key' => 'custom_music_enabled', 'label' => 'Custom Musik', 'type' => 'boolean'],
                ['key' => 'custom_domain_enabled', 'label' => 'Custom Domain', 'type' => 'boolean'],
                ['key' => 'story_section_enabled', 'label' => 'Love Story Section', 'type' => 'boolean'],
                ['key' => 'remove_watermark', 'label' => 'Tanpa Watermark', 'type' => 'boolean'],
            ],
            'Support' => [
                ['key' => 'support_level', 'label' => 'Level Support', 'type' => 'support'],
            ],
        ];

        return view('pricing.compare', compact('packages', 'featureGroups'));
    }

    /**
     * Get package data as JSON (for API/AJAX).
     */
    public function packagesJson()
    {
        $packages = Package::publicDisplay()
            ->get()
            ->map(fn ($package) => $package->getComparisonData());

        return response()->json([
            'packages' => $packages,
        ]);
    }
}
