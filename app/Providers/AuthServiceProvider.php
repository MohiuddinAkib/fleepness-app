<?php

declare(strict_types=1);

namespace App\Providers;

use App\Constants\GateNames;
use App\Policies\LivestreamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define(GateNames::CREATE_LIVESTREAM->value, [LivestreamPolicy::class, 'create']);
        Gate::define(GateNames::GET_LIVESTREAM_PUBLISHER_TOKEN->value, [LivestreamPolicy::class, 'getPublisherToken']);
        Gate::define(GateNames::GET_LIVESTREAM_SUBSCRIBER_TOKEN->value, [LivestreamPolicy::class, 'getSubscriberToken']);
    }
}
