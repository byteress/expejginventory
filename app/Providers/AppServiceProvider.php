<?php

namespace App\Providers;

use IdentityAndAccess\IdentityAndAccessService;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(IIdentityAndAccessService::class, IdentityAndAccessService::class);
    }
}
