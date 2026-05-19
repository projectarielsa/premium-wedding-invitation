<?php

namespace Database\Factories;

use App\Enums\GuestCategory;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    protected $model = Guest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'name' => fake()->name(),
            'slug_token' => Str::random(8),
            'phone_number' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'category' => fake()->randomElement(GuestCategory::cases()),
            'max_attendees' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Set a specific invitation.
     */
    public function forInvitation(Invitation $invitation): static
    {
        return $this->state(fn (array $attributes) => [
            'invitation_id' => $invitation->id,
        ]);
    }

    /**
     * Set as VIP guest.
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => GuestCategory::Vip,
        ]);
    }

    /**
     * Set as family guest.
     */
    public function family(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => GuestCategory::Family,
        ]);
    }
}
