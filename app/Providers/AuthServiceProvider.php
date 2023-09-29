<?php

namespace App\Providers;

use Laravel\Passport\Passport;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\HasApiTokens;

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
        //
        $this->registerPolicies();
        // Passport::routes();
        // Passport::loadKeysFrom(__DIR__ . '/../secrets/oauth');
        // Passport::hashClientSecrets();
        // if(!$this->app->routesAreCached()){
        //     Passport::routes();
        // }
    }
}
