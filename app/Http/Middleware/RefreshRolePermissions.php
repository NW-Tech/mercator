<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RefreshRolePermissions
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $lastUpdate = Cache::get('roles_last_update', 0);
            $sessionAt  = session('auth_permissions_at', 0);

            if ($sessionAt < $lastUpdate) {
                $user = $request->user();
                $user->load('roles.permissions');

                session([
                    'auth_role_ids'       => $user->roles->pluck('id')->all(),
                    'auth_permissions'    => $user->roles
                        ->flatMap->permissions
                        ->pluck('title')
                        ->unique()
                        ->values()
                        ->all(),
                    'auth_permissions_at' => now()->timestamp,
                ]);
            }
        }

        return $next($request);
    }
}
