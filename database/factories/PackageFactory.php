<?php

namespace Database\Factories;

use App\Enums\SupportLevel;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Basic', 'Starter', 'Premium', 'Professional', 'Enterprise']);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'price' => fake()->randomElement([0, 99000, 199000, 399000, 799000]),
            'duration_days' => 365,
            'max_invitations' => 1,
            'max_guests_per_invitation' => 100,
            'max_events_per_invitation' => 2,
            'max_gift_accounts' => 2,
            'max_gallery_images' => 10,
            'rsvp_enabled' => true,
            'gift_enabled' => false,
            'qr_checkin_enabled' => false,
            'analytics_enabled' => false,
            'export_enabled' => false,
            'custom_music_enabled' => false,
            'custom_domain_enabled' => false,
            'whatsapp_blast_enabled' => false,
            'guest_book_enabled' => true,
            'countdown_enabled' => true,
            'story_section_enabled' => false,
            'remove_watermark' => false,
            'support_level' => SupportLevel::Community,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    /**
     * Create a basic/free package.
     */
    public function basic(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Basic',
            'slug' => 'basic',
            'price' => 0,
            'max_invitations' => 1,
            'max_guests_per_invitation' => 50,
            'max_events_per_invitation' => 1,
            'max_gift_accounts' => 0,
            'gift_enabled' => false,
            'qr_checkin_enabled' => false,
            'analytics_enabled' => false,
            'export_enabled' => false,
        ]);
    }

    /**
     * Create a premium package with all features.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 399000,
            'max_invitations' => 5,
            'max_guests_per_invitation' => 500,
            'max_events_per_invitation' => 5,
            'max_gift_accounts' => 5,
            'rsvp_enabled' => true,
            'gift_enabled' => true,
            'qr_checkin_enabled' => true,
            'analytics_enabled' => true,
            'export_enabled' => true,
            'custom_music_enabled' => true,
            'whatsapp_blast_enabled' => true,
            'story_section_enabled' => true,
            'remove_watermark' => true,
            'support_level' => SupportLevel::Priority,
            'is_featured' => true,
            'template_access' => ['all'],
        ]);
    }

    /**
     * Create an inactive package.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
