<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Car' => 'App\Policies\CarPolicy',
        'App\Models\Family' => 'App\Policies\FamilyPolicy',
        'App\Models\Refueling' => 'App\Policies\RefuelingPolicy',
        'App\Models\EstimatedBudget' => 'App\Policies\EstimatedBudgetPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
