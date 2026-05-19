<?php

namespace App\Providers;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Policies\GuestPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PackagePolicy;
use App\Services\PackageLimitService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Invitation::class => InvitationPolicy::class,
        Guest::class => GuestPolicy::class,
        Order::class => OrderPolicy::class,
        Package::class => PackagePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PackageLimitService as singleton
        $this->app->singleton(PackageLimitService::class, function ($app) {
            return new PackageLimitService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
