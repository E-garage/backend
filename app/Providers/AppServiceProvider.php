<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\UserModel;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        UserModel::observe(UserObserver::class);
    }
}
