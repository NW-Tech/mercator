<?php

namespace App\Providers;

use App\Models\Cartographer;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 🧠 Hook global appelé AVANT toutes les autres règles Gate
        Gate::before(function ($user, string $ability) {
            if (! $user) {
                return null;
            }

            // 1. Session web (chemin rapide — set at login)
            if (in_array($ability, session('auth_permissions', []), true)) {
                return true;
            }

            // 2. Contexte API : pas de session web → fallback base de données
            // La présence de cartographer_permissions_at indique un contexte web (loadSessionFor appelé)
            if (! session()->has('auth_permissions') && ! session()->has('cartographer_permissions_at')) {

                // 2a. Permissions de rôle depuis la DB (cache par requête sur $user)
                if (! isset($user->_dbPermissionsCache)) {
                    $user->_dbPermissionsCache = $user->roles()
                        ->with('permissions')
                        ->get()
                        ->flatMap->permissions
                        ->pluck('title')
                        ->all();
                }
                if (in_array($ability, $user->_dbPermissionsCache, true)) {
                    return true;
                }

                // 2b. Pour les droits _access : autoriser si l'utilisateur est cartographe de ce type
                if (str_ends_with($ability, '_access')) {
                    $class = 'App\\Models\\' . Str::studly(substr($ability, 0, -7));
                    if (class_exists($class) && Cartographer::hasAnyFor($user, $class)) {
                        return true;
                    }
                }
            }

            return null;
        });

        Gate::define('edit-object', function (User $user, \Illuminate\Database\Eloquent\Model $object) {
            $ability = Str::snake(class_basename($object)) . '_edit';

            // Session-based check (set at login)
            if (in_array($ability, session('auth_permissions', []), true)) {
                return true;
            }
            // Role-based gate fallback (covers API / test context where session is absent)
            if (Gate::forUser($user)->check($ability)) {
                return true;
            }

            return Cartographer::isAllowed($user, $object);
        });

        Gate::define('show-object', function (User $user, \Illuminate\Database\Eloquent\Model $object) {
            $ability = Str::snake(class_basename($object)) . '_show';

            // Session-based check (set at login)
            if (in_array($ability, session('auth_permissions', []), true)) {
                return true;
            }
            // Role-based gate fallback (covers API / test context where session is absent)
            if (Gate::forUser($user)->check($ability)) {
                return true;
            }

            return Cartographer::isAllowed($user, $object);
        });
    }
}
