<?php

namespace App\Listeners;

use App\Models\Cartographer;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class LoadCartographerPermissions
{
    public function handle(Login $event): void
    {
        if ($event->user instanceof User) {
            Cartographer::loadSessionFor($event->user);
        }
    }
}
