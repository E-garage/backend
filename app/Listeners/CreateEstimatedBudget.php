<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Models\EstimatedBudget;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateEstimatedBudget
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\CarCreated  $event
     * @return void
     */
    public function handle(CarCreated $event)
    {
        if(!$event->car->budget) { //@phpstan-ignore-line
            EstimatedBudget::create([
                'car_id' => $event->car['id'],
            ]);
        }
    }
}
