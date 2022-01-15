<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Events\CarCreated;
use App\Models\Car;

class CarObserver
{
    /**
     * Handle the task "created" event.
     */
    public function created(Car $car): void
    {
        event(new CarCreated($car));
    }
}
