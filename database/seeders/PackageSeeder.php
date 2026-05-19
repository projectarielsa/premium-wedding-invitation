<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            // Basic Package - Free/Entry Level
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Paket dasar untuk undangan pernikahan digital sederhana',
                'badge' => null,
                'price' => 0,
                'original_price' => null,
                'currency' => 'IDR',
                'duration_days' => 365,
                
                // Limits
                'max_invitations' => 1,
                'max_guests_per_invitation' => 100,
                'max_events_per_invitation' => 2,
                'max_gift_accounts' => 2,
                'max_gallery_images' => 5,
                
                // Features
                'rsvp_enabled' => true,
                'gift_enabled' => false,
                'qr_checkin_enabled' => false,
                'analytics_enabled' => false,
                'custom_music_enabled' => false,
                'custom_domain_enabled' => false,
                'export_enabled' => false,
                'whatsapp_blast_enabled' => false,
                'guest_book_enabled' => true,
                'countdown_enabled' => true,
                'story_section_enabled' => false,
                'remove_watermark' => false,
                
                // Template Access - Basic templates only
                'template_access' => ['minimal-white'],
                
                // Support
                'support_level' => 'community',
                'support_response_hours' => null,
                
                // Display
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                
                'features_list' => [
                    '1 Undangan Digital',
                    'Maksimal 100 Tamu',
                    '2 Acara (Akad & Resepsi)',
                    '5 Foto Galeri',
                    'RSVP Online',
                    'Buku Tamu Digital',
                    'Countdown Timer',
                    'Template Minimal White',
                    'Community Support',
                ],
            ],

            // Premium Package - Best Value
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Paket lengkap dengan fitur premium untuk pernikahan impian Anda',
                'badge' => 'Terpopuler',
                'price' => 299000,
                'original_price' => 499000,
                'currency' => 'IDR',
                'duration_days' => 365,
                
                // Limits
                'max_invitations' => 3,
                'max_guests_per_invitation' => 500,
                'max_events_per_invitation' => 5,
                'max_gift_accounts' => 5,
                'max_gallery_images' => 20,
                
                // Features
                'rsvp_enabled' => true,
                'gift_enabled' => true,
                'qr_checkin_enabled' => true,
                'analytics_enabled' => true,
                'custom_music_enabled' => true,
                'custom_domain_enabled' => false,
                'export_enabled' => true,
                'whatsapp_blast_enabled' => true,
                'guest_book_enabled' => true,
                'countdown_enabled' => true,
                'story_section_enabled' => true,
                'remove_watermark' => true,
                
                // Template Access - All standard templates
                'template_access' => ['all'],
                
                // Support
                'support_level' => 'email',
                'support_response_hours' => 48,
                
                // Display
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                
                'features_list' => [
                    '3 Undangan Digital',
                    'Maksimal 500 Tamu per Undangan',
                    '5 Acara',
                    '20 Foto Galeri',
                    'RSVP Online',
                    'Buku Tamu Digital',
                    'Countdown Timer',
                    'Semua Template Premium',
                    'QR Code Check-in',
                    'Amplop Digital (Gift)',
                    'Statistik & Analitik',
                    'Export Data Tamu',
                    'WhatsApp Blast',
                    'Custom Musik',
                    'Love Story Section',
                    'Tanpa Watermark',
                    'Email Support 48 Jam',
                ],
            ],

            // Luxury Package - Full Features
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'description' => 'Paket eksklusif dengan semua fitur tanpa batas untuk momen spesial Anda',
                'badge' => 'Terlengkap',
                'price' => 599000,
                'original_price' => 999000,
                'currency' => 'IDR',
                'duration_days' => 365,
                
                // Limits - Unlimited/High
                'max_invitations' => 999, // Effectively unlimited
                'max_guests_per_invitation' => 99999, // Effectively unlimited
                'max_events_per_invitation' => 10,
                'max_gift_accounts' => 10,
                'max_gallery_images' => 50,
                
                // Features - All enabled
                'rsvp_enabled' => true,
                'gift_enabled' => true,
                'qr_checkin_enabled' => true,
                'analytics_enabled' => true,
                'custom_music_enabled' => true,
                'custom_domain_enabled' => true,
                'export_enabled' => true,
                'whatsapp_blast_enabled' => true,
                'guest_book_enabled' => true,
                'countdown_enabled' => true,
                'story_section_enabled' => true,
                'remove_watermark' => true,
                
                // Template Access - All templates including exclusive
                'template_access' => ['all'],
                
                // Support
                'support_level' => 'priority',
                'support_response_hours' => 24,
                
                // Display
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                
                'features_list' => [
                    'Unlimited Undangan Digital',
                    'Unlimited Tamu',
                    '10 Acara',
                    '50 Foto Galeri',
                    'Semua Fitur Premium',
                    'Custom Domain',
                    'Template Eksklusif',
                    'Priority Support 24 Jam',
                    'Dedicated Account Manager',
                    'Setup Assistance',
                    'Revisi Tanpa Batas',
                ],
            ],
        ];

        foreach ($packages as $packageData) {
            Package::updateOrCreate(
                ['slug' => $packageData['slug']],
                $packageData
            );
        }

        $this->command->info('Packages seeded successfully!');
    }
}
