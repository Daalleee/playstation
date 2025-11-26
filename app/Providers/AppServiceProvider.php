<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        Gate::define('access-admin', fn(User $user) => in_array($user->role, ['admin']));
        Gate::define('access-kasir', fn(User $user) => in_array($user->role, ['kasir','admin']));
        Gate::define('access-pemilik', fn(User $user) => in_array($user->role, ['pemilik','admin']));
        Gate::define('access-pelanggan', fn(User $user) => in_array($user->role, ['pelanggan','admin']));
    }
}
