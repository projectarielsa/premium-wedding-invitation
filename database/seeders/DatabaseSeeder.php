<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run package seeder
        $this->call([
            PackageSeeder::class,
            PaymentSettingsSeeder::class,
        ]);

        // Create test user (customer)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password'), 'role' => UserRole::Customer, 'email_verified_at' => now()]
        );

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => UserRole::Admin, 'email_verified_at' => now()]
        );

        // Create super admin user
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'role' => UserRole::SuperAdmin, 'email_verified_at' => now()]
        );
    }
}
