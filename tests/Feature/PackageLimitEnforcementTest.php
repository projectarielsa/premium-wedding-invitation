<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Package;
use App\Models\User;
use App\Services\PackageLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageLimitEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Package $basicPackage;
    protected Package $premiumPackage;

    protected function setUp(): void
    {
        parent::setUp();

        // Create packages
        $this->basicPackage = Package::factory()->basic()->create();
        $this->premiumPackage = Package::factory()->premium()->create();

        // Create user with basic package
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
            'active_package_id' => $this->basicPackage->id,
            'package_started_at' => now(),
            'package_expires_at' => now()->addYear(),
        ]);
    }

    // =========================================================================
    // INVITATION LIMIT TESTS
    // =========================================================================

    public function test_user_can_create_invitation_within_limit(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('invitations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.create');
    }

    public function test_user_cannot_create_invitation_when_limit_reached(): void
    {
        // Create invitation to reach the limit (basic = 1)
        Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.limit-reached');
    }

    public function test_user_cannot_store_invitation_when_limit_reached(): void
    {
        // Create invitation to reach the limit
        Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->post(route('invitations.store'), [
            'bride_name' => 'Sarah',
            'groom_name' => 'John',
            'event_date' => now()->addMonth()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('pricing'));
        $response->assertSessionHas('upgrade_required', true);
    }

    public function test_admin_can_create_invitation_regardless_of_limit(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
            'active_package_id' => $this->basicPackage->id,
            'package_started_at' => now(),
            'package_expires_at' => now()->addYear(),
        ]);

        // Create invitation to reach the limit
        Invitation::factory()->forUser($admin)->create();

        $this->actingAs($admin);

        $response = $this->get(route('invitations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invitations.create');
    }

    // =========================================================================
    // GUEST LIMIT TESTS
    // =========================================================================

    public function test_user_can_add_guest_within_limit(): void
    {
        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->post(route('invitations.guests.store', $invitation), [
            'name' => 'Test Guest',
            'phone_number' => '081234567890',
            'category' => 'friend',
            'max_attendees' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guests', ['name' => 'Test Guest']);
    }

    public function test_user_cannot_add_guest_when_limit_reached(): void
    {
        $invitation = Invitation::factory()->forUser($this->user)->create();
        
        // Create guests up to the limit (basic = 50)
        Guest::factory()->count(50)->forInvitation($invitation)->create();

        $this->actingAs($this->user);

        $response = $this->post(route('invitations.guests.store', $invitation), [
            'name' => 'One More Guest',
            'phone_number' => '081234567890',
            'category' => 'friend',
            'max_attendees' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('guests', ['name' => 'One More Guest']);
    }

    public function test_admin_can_add_guest_regardless_of_limit(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
            'active_package_id' => $this->basicPackage->id,
            'package_started_at' => now(),
            'package_expires_at' => now()->addYear(),
        ]);

        $invitation = Invitation::factory()->forUser($admin)->create();
        
        // Create guests up to the limit
        Guest::factory()->count(50)->forInvitation($invitation)->create();

        $this->actingAs($admin);

        $response = $this->post(route('invitations.guests.store', $invitation), [
            'name' => 'Admin Added Guest',
            'phone_number' => '081234567890',
            'category' => 'friend',
            'max_attendees' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guests', ['name' => 'Admin Added Guest']);
    }

    // =========================================================================
    // FEATURE ACCESS TESTS
    // =========================================================================

    public function test_user_without_analytics_feature_sees_locked_page(): void
    {
        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.analytics.index', $invitation));

        $response->assertStatus(200);
        $response->assertViewIs('analytics.locked');
    }

    public function test_user_with_analytics_feature_can_access_analytics(): void
    {
        // Assign premium package
        $this->user->update([
            'active_package_id' => $this->premiumPackage->id,
        ]);

        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.analytics.index', $invitation));

        $response->assertStatus(200);
        $response->assertViewIs('analytics.index');
    }

    public function test_user_without_export_feature_cannot_export(): void
    {
        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.export.guests', $invitation));

        $response->assertStatus(403);
    }

    public function test_user_with_export_feature_can_export(): void
    {
        // Assign premium package
        $this->user->update([
            'active_package_id' => $this->premiumPackage->id,
        ]);

        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.export.guests', $invitation));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_user_without_checkin_feature_sees_locked_page(): void
    {
        $invitation = Invitation::factory()->forUser($this->user)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('invitations.checkin.index', $invitation));

        $response->assertStatus(200);
        $response->assertViewIs('checkin.locked');
    }

    public function test_admin_can_access_any_feature_regardless_of_package(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
            'active_package_id' => $this->basicPackage->id,
            'package_started_at' => now(),
            'package_expires_at' => now()->addYear(),
        ]);

        $invitation = Invitation::factory()->forUser($admin)->create();

        $this->actingAs($admin);

        // Analytics
        $response = $this->get(route('invitations.analytics.index', $invitation));
        $response->assertStatus(200);
        $response->assertViewIs('analytics.index');

        // Check-in
        $response = $this->get(route('invitations.checkin.index', $invitation));
        $response->assertStatus(200);
        $response->assertViewIs('checkin.index');

        // Export
        $response = $this->get(route('invitations.export.guests', $invitation));
        $response->assertStatus(200);
    }

    // =========================================================================
    // PACKAGE LIMIT SERVICE TESTS
    // =========================================================================

    public function test_package_limit_service_invitation_check(): void
    {
        $service = app(PackageLimitService::class);

        // Should be allowed (0 invitations, limit is 1)
        $result = $service->canCreateInvitation($this->user);
        $this->assertTrue($result->isAllowed());

        // Create one invitation
        Invitation::factory()->forUser($this->user)->create();

        // Should not be allowed (1 invitation, limit is 1)
        $result = $service->canCreateInvitation($this->user);
        $this->assertFalse($result->isAllowed());
        $this->assertTrue($result->needsUpgrade());
    }

    public function test_package_limit_service_guest_check(): void
    {
        $service = app(PackageLimitService::class);
        $invitation = Invitation::factory()->forUser($this->user)->create();

        // Should be allowed (0 guests, limit is 50)
        $result = $service->canAddGuests($this->user, $invitation, 1);
        $this->assertTrue($result->isAllowed());
        $this->assertEquals(50, $result->remaining);

        // Create 50 guests
        Guest::factory()->count(50)->forInvitation($invitation)->create();

        // Should not be allowed
        $result = $service->canAddGuests($this->user, $invitation, 1);
        $this->assertFalse($result->isAllowed());
        $this->assertEquals(0, $result->remaining);
    }

    public function test_package_limit_service_feature_check(): void
    {
        $service = app(PackageLimitService::class);

        // Basic package should not have analytics
        $result = $service->canUseFeature($this->user, 'analytics');
        $this->assertFalse($result->isAllowed());

        // Upgrade to premium
        $this->user->update(['active_package_id' => $this->premiumPackage->id]);

        // Premium should have analytics
        $result = $service->canUseFeature($this->user, 'analytics');
        $this->assertTrue($result->isAllowed());
    }

    public function test_usage_summary_returns_correct_data(): void
    {
        $service = app(PackageLimitService::class);
        $invitation = Invitation::factory()->forUser($this->user)->create();
        Guest::factory()->count(10)->forInvitation($invitation)->create();

        $summary = $service->getUsageSummary($this->user);

        $this->assertTrue($summary['has_package']);
        $this->assertEquals('Basic', $summary['package']['name']);
        $this->assertEquals(1, $summary['limits']['invitations']['current']);
        $this->assertEquals(1, $summary['limits']['invitations']['max']);
        $this->assertFalse($summary['features']['analytics']);
        $this->assertFalse($summary['features']['export']);
    }
}
