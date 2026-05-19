<?php

namespace Database\Factories;

use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $brideName = fake()->firstName('female');
        $groomName = fake()->firstName('male');
        
        return [
            'user_id' => User::factory(),
            'slug' => Str::slug($brideName . ' ' . $groomName . ' ' . Str::random(4)),
            'title' => "Undangan Pernikahan {$brideName} & {$groomName}",
            'bride_name' => $brideName,
            'groom_name' => $groomName,
            'event_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
            'status' => InvitationStatus::Draft,
            'view_count' => 0,
            'unique_visitor_count' => 0,
        ];
    }

    /**
     * Set the invitation as published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::Published,
            'published_at' => now(),
        ]);
    }

    /**
     * Set the invitation as draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::Draft,
            'published_at' => null,
        ]);
    }

    /**
     * Set a specific user as the owner.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
