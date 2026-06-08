<?php

namespace App\Providers;

use App\Events\CartographerModifiedObject;
use App\Listeners\LoadCartographerPermissions;
use App\Listeners\NotifyCartographerModification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Keycloak\KeycloakExtendSocialite::class,
        ],

        Login::class => [
            LoadCartographerPermissions::class,
        ],

        CartographerModifiedObject::class => [
            NotifyCartographerModification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
