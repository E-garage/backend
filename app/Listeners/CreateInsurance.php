<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Models\Insurance;

class CreateInsurance
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
        if (!$event->car->insurance) { //@phpstan-ignore-line
            Insurance::create([
                'car_id' => $event->car['id'],
            ]);
        }
    }
}
