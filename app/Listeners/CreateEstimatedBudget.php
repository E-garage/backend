<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Models\EstimatedBudget;

class CreateEstimatedBudget
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(CarCreated $event)
    {
        if (!$event->car->budget()->exists()) {
            EstimatedBudget::create([
                'car_id' => $event->car['id'],
            ]);
        }
    }
}
