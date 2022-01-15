<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Models\Inspection;

class CreateInspection
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(CarCreated $event)
    {
        if (!$event->car->inspection) {
            Inspection::create([
                'car_id' => $event->car['id'],
            ]);
        }
    }
}
