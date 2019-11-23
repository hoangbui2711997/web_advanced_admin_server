<?php

namespace App\Providers;

use App\Custom\Passport\CustomToken;
use App\Utils\EloquentUserCustom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::useTokenModel(CustomToken::class);
    }

    public function register()
    {
        Auth::provider('custom-eloquent', function($app, array $config) {
            return new EloquentUserCustom($app['hash'], $config['model']);
        });
    }
}
