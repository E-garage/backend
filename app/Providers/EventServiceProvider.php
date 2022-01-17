<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Events\CarCreated;
use App\Listeners\CreateEstimatedBudget;
use App\Listeners\CreateInspection;
use App\Listeners\CreateInsurance;
use App\Listeners\CreateLastParkedLocation;
use App\Listeners\DumpDatabase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateLastParkedLocation::class,
        ],

        CarCreated::class => [
            CreateInsurance::class,
            CreateInspection::class,
            CreateEstimatedBudget::class,
        ],

        MigrationsStarted::class => [
            DumpDatabase::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
