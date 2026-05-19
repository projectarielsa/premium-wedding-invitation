<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Enums\EventType;
use App\Enums\GiftAccountType;
use App\Enums\GuestCategory;
use App\Enums\InvitationStatus;
use App\Models\Event;
use App\Models\GiftAccount;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Rsvp;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Demo seeder for testing and demonstration purposes.
 * Creates sample data for all major features.
 */
class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating demo data...');

        // Create demo user
        $user = $this->createDemoUser();

        // Create templates
        $templates = $this->createTemplates();

        // Create invitations with all related data
        $this->createDemoInvitation($user, $templates->first());
        $this->createSecondaryInvitation($user, $templates->skip(1)->first());

        $this->command->info('Demo data created successfully!');
        $this->command->info('');
        $this->command->info('Demo Login:');
        $this->command->info('  Email: demo@wedding.test');
        $this->command->info('  Password: password');
    }

    /**
     * Create demo user.
     */
    private function createDemoUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'demo@wedding.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'otp_verified_at' => now(),
            ]
        );
    }

    /**
     * Create demo templates.
     */
    private function createTemplates()
    {
        $templates = [
            [
                'name' => 'Elegant Rose',
                'slug' => 'elegant-rose',
                'description' => 'A timeless and elegant rose-themed template perfect for classic weddings.',
                'category' => 'classic',
                'preview_image' => 'templates/elegant-rose.jpg',
                'theme_config' => [
                    'primary_color' => '#d4a373',
                    'secondary_color' => '#fefae0',
                    'accent_color' => '#bc6c25',
                    'font_heading' => 'Playfair Display',
                    'font_body' => 'Lato',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Modern Minimalist',
                'slug' => 'modern-minimalist',
                'description' => 'Clean and modern design for contemporary couples.',
                'category' => 'modern',
                'preview_image' => 'templates/modern-minimalist.jpg',
                'theme_config' => [
                    'primary_color' => '#2d3436',
                    'secondary_color' => '#ffffff',
                    'accent_color' => '#00b894',
                    'font_heading' => 'Montserrat',
                    'font_body' => 'Open Sans',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Rustic Garden',
                'slug' => 'rustic-garden',
                'description' => 'Natural and rustic theme with garden elements.',
                'category' => 'rustic',
                'preview_image' => 'templates/rustic-garden.jpg',
                'theme_config' => [
                    'primary_color' => '#606c38',
                    'secondary_color' => '#fefae0',
                    'accent_color' => '#dda15e',
                    'font_heading' => 'Cormorant Garamond',
                    'font_body' => 'Nunito',
                ],
                'is_active' => true,
                'is_premium' => true,
            ],
        ];

        foreach ($templates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }

        return Template::all();
    }

    /**
     * Create main demo invitation with full data.
     */
    private function createDemoInvitation(User $user, ?Template $template): Invitation
    {
        $eventDate = now()->addMonths(3)->setTime(10, 0);

        $invitation = Invitation::updateOrCreate(
            ['slug' => 'sarah-john-demo'],
            [
                'user_id' => $user->id,
                'template_id' => $template?->id,
                'title' => 'Sarah & John Wedding',
                'bride_name' => 'Sarah Amelia',
                'groom_name' => 'John Michael',
                'bride_parent' => 'Mr. & Mrs. Robert Anderson',
                'groom_parent' => 'Mr. & Mrs. William Smith',
                'opening_message' => 'With grateful hearts and joyful spirits, we invite you to share in our happiness as we begin our journey together.',
                'event_date' => $eventDate->toDateString(),
                'location' => 'Grand Ballroom, The Ritz Carlton Jakarta',
                'google_maps_url' => 'https://maps.google.com/?q=The+Ritz+Carlton+Jakarta',
                'dress_code' => 'Formal / Semi-formal (Black tie optional)',
                'status' => InvitationStatus::Published,
                'published_at' => now(),
                'view_count' => 1250,
                'unique_visitor_count' => 487,
                'settings' => [
                    'rsvp_enabled' => true,
                    'gift_enabled' => true,
                    'guest_book_enabled' => true,
                    'countdown_enabled' => true,
                    'music_autoplay' => false,
                    'show_guest_count' => true,
                    'require_attendance_count' => true,
                    'max_attendance_per_guest' => 5,
                ],
            ]
        );

        // Create events
        $this->createEvents($invitation, $eventDate);

        // Create gift accounts
        $this->createGiftAccounts($invitation);

        // Create guests with various statuses
        $this->createGuests($invitation);

        return $invitation;
    }

    /**
     * Create secondary invitation.
     */
    private function createSecondaryInvitation(User $user, ?Template $template): Invitation
    {
        return Invitation::updateOrCreate(
            ['slug' => 'anna-david-demo'],
            [
                'user_id' => $user->id,
                'template_id' => $template?->id,
                'title' => 'Anna & David Wedding',
                'bride_name' => 'Anna Kartika',
                'groom_name' => 'David Pratama',
                'bride_parent' => 'Bpk. & Ibu Susanto',
                'groom_parent' => 'Bpk. & Ibu Wijaya',
                'opening_message' => 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara pernikahan kami.',
                'event_date' => now()->addMonths(5)->toDateString(),
                'location' => 'Hotel Mulia Senayan, Jakarta',
                'google_maps_url' => 'https://maps.google.com/?q=Hotel+Mulia+Senayan',
                'status' => InvitationStatus::Draft,
                'settings' => [
                    'rsvp_enabled' => true,
                    'gift_enabled' => true,
                    'countdown_enabled' => true,
                ],
            ]
        );
    }

    /**
     * Create events for invitation.
     */
    private function createEvents(Invitation $invitation, $eventDate): void
    {
        $events = [
            [
                'name' => 'Akad Nikah',
                'type' => EventType::Ceremony,
                'event_date' => $eventDate->toDateString(),
                'start_time' => '09:00',
                'end_time' => '10:30',
                'venue_name' => 'Masjid Agung Istiqlal',
                'venue_address' => 'Jl. Taman Wijaya Kusuma, Jakarta',
                'google_maps_url' => 'https://maps.google.com/?q=Masjid+Istiqlal',
                'description' => 'Holy matrimony ceremony',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Wedding Reception',
                'type' => EventType::Reception,
                'event_date' => $eventDate->toDateString(),
                'start_time' => '11:00',
                'end_time' => '15:00',
                'venue_name' => 'Grand Ballroom, The Ritz Carlton Jakarta',
                'venue_address' => 'Jl. DR. Ide Anak Agung Gde Agung Kav. E.1.1 No.1',
                'google_maps_url' => 'https://maps.google.com/?q=The+Ritz+Carlton+Jakarta',
                'description' => 'Wedding reception and dinner',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'name' => $event['name'],
                ],
                $event
            );
        }
    }

    /**
     * Create gift accounts for invitation.
     */
    private function createGiftAccounts(Invitation $invitation): void
    {
        $accounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_type' => GiftAccountType::Bank,
                'account_name' => 'Sarah Amelia',
                'account_number' => '1234567890',
                'description' => 'Main account for monetary gifts',
                'sort_order' => 1,
                'is_active' => true,
                'view_count' => 89,
                'copy_count' => 45,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_type' => GiftAccountType::Bank,
                'account_name' => 'John Michael Smith',
                'account_number' => '0987654321',
                'description' => 'Secondary account',
                'sort_order' => 2,
                'is_active' => true,
                'view_count' => 32,
                'copy_count' => 18,
            ],
            [
                'bank_name' => 'GoPay',
                'account_type' => GiftAccountType::EWallet,
                'account_name' => 'Sarah Amelia',
                'account_number' => '081234567890',
                'description' => 'E-wallet option',
                'sort_order' => 3,
                'is_active' => true,
                'view_count' => 56,
                'copy_count' => 28,
            ],
        ];

        foreach ($accounts as $account) {
            GiftAccount::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'bank_name' => $account['bank_name'],
                ],
                $account
            );
        }
    }

    /**
     * Create demo guests with various statuses.
     */
    private function createGuests(Invitation $invitation): void
    {
        $guests = [
            // VIP Guests
            ['name' => 'Dr. Ahmad Suryadi', 'category' => GuestCategory::Vip, 'phone' => '081234567001', 'status' => 'attending', 'count' => 4],
            ['name' => 'Ir. Budi Hartono', 'category' => GuestCategory::Vip, 'phone' => '081234567002', 'status' => 'attending', 'count' => 2],
            
            // Family
            ['name' => 'Robert Anderson (Father)', 'category' => GuestCategory::Family, 'phone' => '081234567010', 'status' => 'attending', 'count' => 2],
            ['name' => 'Emily Anderson (Sister)', 'category' => GuestCategory::Family, 'phone' => '081234567011', 'status' => 'attending', 'count' => 3],
            ['name' => 'Michael Anderson (Brother)', 'category' => GuestCategory::Family, 'phone' => '081234567012', 'status' => 'attending', 'count' => 4],
            ['name' => 'Grandma Rose', 'category' => GuestCategory::Family, 'phone' => '081234567013', 'status' => 'attending', 'count' => 1],
            ['name' => 'Uncle James & Family', 'category' => GuestCategory::Family, 'phone' => '081234567014', 'status' => 'maybe', 'count' => 5],
            
            // Friends
            ['name' => 'Jessica Williams', 'category' => GuestCategory::Friend, 'phone' => '081234567020', 'status' => 'attending', 'count' => 1],
            ['name' => 'Amanda Chen', 'category' => GuestCategory::Friend, 'phone' => '081234567021', 'status' => 'attending', 'count' => 2],
            ['name' => 'Kevin Martinez', 'category' => GuestCategory::Friend, 'phone' => '081234567022', 'status' => 'not_attending', 'count' => 0],
            ['name' => 'Rachel Green', 'category' => GuestCategory::Friend, 'phone' => '081234567023', 'status' => 'pending'],
            ['name' => 'Monica Geller', 'category' => GuestCategory::Friend, 'phone' => '081234567024', 'status' => 'attending', 'count' => 2, 'checked_in' => true],
            ['name' => 'Chandler Bing', 'category' => GuestCategory::Friend, 'phone' => '081234567025', 'status' => 'attending', 'count' => 2, 'checked_in' => true],
            ['name' => 'Ross Geller', 'category' => GuestCategory::Friend, 'phone' => '081234567026', 'status' => 'maybe', 'count' => 1],
            ['name' => 'Phoebe Buffay', 'category' => GuestCategory::Friend, 'phone' => '081234567027', 'status' => 'attending', 'count' => 1],
            ['name' => 'Joey Tribbiani', 'category' => GuestCategory::Friend, 'phone' => '081234567028', 'status' => 'pending'],
            
            // Colleagues
            ['name' => 'Mr. Thompson (Boss)', 'category' => GuestCategory::Colleague, 'phone' => '081234567030', 'status' => 'attending', 'count' => 2],
            ['name' => 'Lisa Park', 'category' => GuestCategory::Colleague, 'phone' => '081234567031', 'status' => 'attending', 'count' => 1],
            ['name' => 'David Kim', 'category' => GuestCategory::Colleague, 'phone' => '081234567032', 'status' => 'not_attending', 'count' => 0],
            ['name' => 'Sarah Johnson', 'category' => GuestCategory::Colleague, 'phone' => '081234567033', 'status' => 'pending'],
            
            // Neighbors
            ['name' => 'Mr. & Mrs. Wilson', 'category' => GuestCategory::Neighbor, 'phone' => '081234567040', 'status' => 'attending', 'count' => 2],
            ['name' => 'The Garcia Family', 'category' => GuestCategory::Neighbor, 'phone' => '081234567041', 'status' => 'maybe', 'count' => 4],
        ];

        foreach ($guests as $guestData) {
            $guest = Guest::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'phone_number' => $guestData['phone'],
                ],
                [
                    'name' => $guestData['name'],
                    'category' => $guestData['category'],
                    'whatsapp' => $guestData['phone'],
                    'email' => Str::slug($guestData['name']) . '@example.com',
                    'max_attendees' => 5,
                    'unique_visit_count' => rand(0, 10),
                    'first_visited_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                    'last_visited_at' => rand(0, 1) ? now()->subDays(rand(0, 7)) : null,
                    'whatsapp_sent_at' => rand(0, 1) ? now()->subDays(rand(1, 14)) : null,
                    'checked_in_at' => ($guestData['checked_in'] ?? false) ? now()->subHours(rand(1, 5)) : null,
                    'checked_in_by' => ($guestData['checked_in'] ?? false) ? 'Reception Staff' : null,
                ]
            );

            // Create RSVP if status is not pending
            if (isset($guestData['status']) && $guestData['status'] !== 'pending') {
                Rsvp::updateOrCreate(
                    [
                        'invitation_id' => $invitation->id,
                        'guest_id' => $guest->id,
                    ],
                    [
                        'attendance_status' => AttendanceStatus::from($guestData['status']),
                        'attendance_count' => $guestData['count'] ?? 0,
                        'message' => $this->getRandomMessage($guestData['status']),
                        'responded_at' => now()->subDays(rand(1, 20)),
                        'ip_address' => '192.168.1.' . rand(1, 255),
                        'user_agent' => 'Mozilla/5.0 (Demo Browser)',
                    ]
                );
            }
        }
    }

    /**
     * Get random RSVP message based on status.
     */
    private function getRandomMessage(string $status): ?string
    {
        $messages = [
            'attending' => [
                'Congratulations! We are so happy for you both!',
                'Can\'t wait to celebrate with you!',
                'Looking forward to the big day!',
                'So excited to be part of your special day!',
                null,
            ],
            'not_attending' => [
                'So sorry we can\'t make it. Wishing you all the best!',
                'Unfortunately we have a prior commitment. Congratulations!',
                null,
            ],
            'maybe' => [
                'Will confirm closer to the date.',
                'Checking our schedule, will let you know soon.',
                null,
            ],
        ];

        $pool = $messages[$status] ?? [null];
        return $pool[array_rand($pool)];
    }
}
