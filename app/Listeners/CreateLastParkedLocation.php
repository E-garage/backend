<?php

namespace App\Listeners;

use App\Models\LastParkedLocation;
use Illuminate\Auth\Events\Registered;

class CreateLastParkedLocation
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!$event->user->location) { //@phpstan-ignore-line
            LastParkedLocation::create([
                'user_id' => $event->user['id'],
            ]);
        }
    }
}
