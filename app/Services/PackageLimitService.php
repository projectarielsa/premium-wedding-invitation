<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invitation;
use App\Models\Package;
use App\Models\Template;
use App\Models\User;

/**
 * Service for enforcing package limits across the application.
 */
class PackageLimitService
{
    /**
     * Check if user can create a new invitation.
     */
    public function canCreateInvitation(User $user): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif. Silakan pilih paket untuk melanjutkan.',
                upgradeRequired: true
            );
        }

        $currentCount = $user->invitations()->count();
        $maxAllowed = $package->max_invitations;

        if ($currentCount >= $maxAllowed) {
            return new LimitCheckResult(
                allowed: false,
                message: "Anda telah mencapai batas maksimal {$maxAllowed} undangan untuk paket {$package->name}.",
                upgradeRequired: true,
                currentUsage: $currentCount,
                maxAllowed: $maxAllowed,
                feature: 'invitations'
            );
        }

        return new LimitCheckResult(
            allowed: true,
            currentUsage: $currentCount,
            maxAllowed: $maxAllowed,
            feature: 'invitations'
        );
    }

    /**
     * Check if user can add more guests to an invitation.
     */
    public function canAddGuests(User $user, Invitation $invitation, int $countToAdd = 1): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif.',
                upgradeRequired: true
            );
        }

        $currentCount = $invitation->guests()->count();
        $maxAllowed = $package->max_guests_per_invitation;
        $remaining = $maxAllowed - $currentCount;

        if ($remaining < $countToAdd) {
            return new LimitCheckResult(
                allowed: false,
                message: "Anda hanya dapat menambahkan {$remaining} tamu lagi. Paket {$package->name} memiliki batas {$maxAllowed} tamu per undangan.",
                upgradeRequired: true,
                currentUsage: $currentCount,
                maxAllowed: $maxAllowed,
                remaining: $remaining,
                feature: 'guests'
            );
        }

        return new LimitCheckResult(
            allowed: true,
            currentUsage: $currentCount,
            maxAllowed: $maxAllowed,
            remaining: $remaining,
            feature: 'guests'
        );
    }

    /**
     * Check if user can add more events to an invitation.
     */
    public function canAddEvent(User $user, Invitation $invitation): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif.',
                upgradeRequired: true
            );
        }

        $currentCount = $invitation->events()->count();
        $maxAllowed = $package->max_events_per_invitation;

        if ($currentCount >= $maxAllowed) {
            return new LimitCheckResult(
                allowed: false,
                message: "Anda telah mencapai batas maksimal {$maxAllowed} acara untuk paket {$package->name}.",
                upgradeRequired: true,
                currentUsage: $currentCount,
                maxAllowed: $maxAllowed,
                feature: 'events'
            );
        }

        return new LimitCheckResult(
            allowed: true,
            currentUsage: $currentCount,
            maxAllowed: $maxAllowed,
            feature: 'events'
        );
    }

    /**
     * Check if user can add more gift accounts to an invitation.
     */
    public function canAddGiftAccount(User $user, Invitation $invitation): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif.',
                upgradeRequired: true
            );
        }

        if (!$package->gift_enabled) {
            return new LimitCheckResult(
                allowed: false,
                message: "Fitur amplop digital tidak tersedia di paket {$package->name}. Upgrade untuk mengaktifkan fitur ini.",
                upgradeRequired: true,
                feature: 'gift'
            );
        }

        $currentCount = $invitation->giftAccounts()->count();
        $maxAllowed = $package->max_gift_accounts;

        if ($currentCount >= $maxAllowed) {
            return new LimitCheckResult(
                allowed: false,
                message: "Anda telah mencapai batas maksimal {$maxAllowed} rekening gift untuk paket {$package->name}.",
                upgradeRequired: true,
                currentUsage: $currentCount,
                maxAllowed: $maxAllowed,
                feature: 'gift_accounts'
            );
        }

        return new LimitCheckResult(
            allowed: true,
            currentUsage: $currentCount,
            maxAllowed: $maxAllowed,
            feature: 'gift_accounts'
        );
    }

    /**
     * Check if user can add more gallery images to an invitation.
     */
    public function canAddGalleryImages(User $user, Invitation $invitation, int $countToAdd = 1): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif.',
                upgradeRequired: true
            );
        }

        $currentCount = count($invitation->gallery ?? []);
        $maxAllowed = $package->max_gallery_images;
        $remaining = $maxAllowed - $currentCount;

        if ($remaining < $countToAdd) {
            return new LimitCheckResult(
                allowed: false,
                message: "Anda hanya dapat menambahkan {$remaining} foto lagi. Paket {$package->name} memiliki batas {$maxAllowed} foto galeri.",
                upgradeRequired: true,
                currentUsage: $currentCount,
                maxAllowed: $maxAllowed,
                remaining: $remaining,
                feature: 'gallery'
            );
        }

        return new LimitCheckResult(
            allowed: true,
            currentUsage: $currentCount,
            maxAllowed: $maxAllowed,
            remaining: $remaining,
            feature: 'gallery'
        );
    }

    /**
     * Check if user can access a specific template.
     */
    public function canAccessTemplate(User $user, Template $template): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: !$template->is_premium,
                message: $template->is_premium 
                    ? 'Template premium membutuhkan paket berbayar.' 
                    : null,
                upgradeRequired: $template->is_premium,
                feature: 'template'
            );
        }

        if ($package->canAccessTemplate($template)) {
            return new LimitCheckResult(allowed: true, feature: 'template');
        }

        return new LimitCheckResult(
            allowed: false,
            message: "Template {$template->name} tidak tersedia di paket {$package->name}. Upgrade paket Anda untuk mengakses template ini.",
            upgradeRequired: true,
            feature: 'template'
        );
    }

    /**
     * Check if user can use a specific feature.
     */
    public function canUseFeature(User $user, string $feature): LimitCheckResult
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return new LimitCheckResult(
                allowed: false,
                message: 'Anda belum memiliki paket aktif.',
                upgradeRequired: true,
                feature: $feature
            );
        }

        $featureLabels = [
            'rsvp' => 'RSVP Online',
            'gift' => 'Amplop Digital',
            'qr_checkin' => 'QR Code Check-in',
            'analytics' => 'Statistik & Analitik',
            'custom_music' => 'Custom Musik',
            'custom_domain' => 'Custom Domain',
            'export' => 'Export Data',
            'whatsapp_blast' => 'WhatsApp Blast',
            'guest_book' => 'Buku Tamu Digital',
            'countdown' => 'Countdown Timer',
            'story_section' => 'Love Story Section',
            'remove_watermark' => 'Tanpa Watermark',
        ];

        $featureLabel = $featureLabels[$feature] ?? $feature;

        if (!$package->hasFeature($feature)) {
            return new LimitCheckResult(
                allowed: false,
                message: "Fitur {$featureLabel} tidak tersedia di paket {$package->name}. Upgrade untuk mengaktifkan fitur ini.",
                upgradeRequired: true,
                feature: $feature
            );
        }

        return new LimitCheckResult(allowed: true, feature: $feature);
    }

    /**
     * Check if user can export data.
     */
    public function canExportData(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'export');
    }

    /**
     * Check if user can access analytics.
     */
    public function canAccessAnalytics(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'analytics');
    }

    /**
     * Check if user can use QR check-in.
     */
    public function canUseQrCheckin(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'qr_checkin');
    }

    /**
     * Check if user can use WhatsApp blast.
     */
    public function canUseWhatsappBlast(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'whatsapp_blast');
    }

    /**
     * Check if user can use custom music.
     */
    public function canUseCustomMusic(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'custom_music');
    }

    /**
     * Check if user can use custom domain.
     */
    public function canUseCustomDomain(User $user): LimitCheckResult
    {
        return $this->canUseFeature($user, 'custom_domain');
    }

    /**
     * Get comprehensive usage summary for a user.
     */
    public function getUsageSummary(User $user): array
    {
        $package = $user->getEffectivePackage();
        
        if (!$package) {
            return [
                'has_package' => false,
                'package' => null,
                'limits' => [],
                'features' => [],
            ];
        }

        $totalGuests = $user->invitations()->withCount('guests')->get()->sum('guests_count');
        $totalInvitations = $user->invitations()->count();

        return [
            'has_package' => true,
            'package' => [
                'name' => $package->name,
                'slug' => $package->slug,
                'expires_at' => $user->package_expires_at,
                'days_remaining' => $user->days_until_package_expires,
                'is_expiring_soon' => $user->isPackageExpiringSoon(),
            ],
            'limits' => [
                'invitations' => [
                    'current' => $totalInvitations,
                    'max' => $package->max_invitations,
                    'percentage' => $package->max_invitations > 0 
                        ? round(($totalInvitations / $package->max_invitations) * 100) 
                        : 0,
                ],
                'guests_per_invitation' => [
                    'max' => $package->max_guests_per_invitation,
                ],
                'events_per_invitation' => [
                    'max' => $package->max_events_per_invitation,
                ],
                'gift_accounts' => [
                    'max' => $package->max_gift_accounts,
                ],
                'gallery_images' => [
                    'max' => $package->max_gallery_images,
                ],
            ],
            'features' => [
                'rsvp' => $package->rsvp_enabled,
                'gift' => $package->gift_enabled,
                'qr_checkin' => $package->qr_checkin_enabled,
                'analytics' => $package->analytics_enabled,
                'custom_music' => $package->custom_music_enabled,
                'custom_domain' => $package->custom_domain_enabled,
                'export' => $package->export_enabled,
                'whatsapp_blast' => $package->whatsapp_blast_enabled,
                'guest_book' => $package->guest_book_enabled,
                'countdown' => $package->countdown_enabled,
                'story_section' => $package->story_section_enabled,
                'remove_watermark' => $package->remove_watermark,
            ],
            'support' => [
                'level' => $package->support_level->value,
                'response_hours' => $package->support_response_hours,
            ],
        ];
    }

    /**
     * Get upgrade suggestions based on what user is trying to do.
     */
    public function getUpgradeSuggestion(User $user, string $feature): ?Package
    {
        $currentPackage = $user->getEffectivePackage();
        $currentPrice = $currentPackage?->price ?? 0;

        // Find the cheapest package that has the requested feature
        return Package::active()
            ->where('price', '>', $currentPrice)
            ->ordered()
            ->get()
            ->first(function (Package $package) use ($feature) {
                return $package->hasFeature($feature);
            });
    }
}

/**
 * Result object for limit checks.
 */
class LimitCheckResult
{
    public function __construct(
        public readonly bool $allowed,
        public readonly ?string $message = null,
        public readonly bool $upgradeRequired = false,
        public readonly ?int $currentUsage = null,
        public readonly ?int $maxAllowed = null,
        public readonly ?int $remaining = null,
        public readonly ?string $feature = null,
    ) {}

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'allowed' => $this->allowed,
            'message' => $this->message,
            'upgrade_required' => $this->upgradeRequired,
            'current_usage' => $this->currentUsage,
            'max_allowed' => $this->maxAllowed,
            'remaining' => $this->remaining,
            'feature' => $this->feature,
        ];
    }

    /**
     * Check if allowed.
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    /**
     * Check if upgrade is required.
     */
    public function needsUpgrade(): bool
    {
        return $this->upgradeRequired;
    }
}
