<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\UsersRole;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register View Composer to pass 'roles' to all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                // Get roles associated with the logged-in user
                $roles = UsersRole::where('id_users', $user->id)->pluck('id_role');
                // Share 'roles' with all views
                $view->with('roles_user', $roles);
            }
        });
    }
}
