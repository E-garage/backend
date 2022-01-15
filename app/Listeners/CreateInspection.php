<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Models\Inspection;

class CreateInspection
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle(CarCreated $event)
    {
        if (!$event->car->inspection) { //@phpstan-ignore-line
            Inspection::create([
                'car_id' => $event->car['id'],
            ]);
        }
    }
}
