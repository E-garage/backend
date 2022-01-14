<?php

declare(strict_types = 1);

namespace App\Events;

use App\Models\Car;
use Illuminate\Queue\SerializesModels;

class CarCreated
{
    use SerializesModels;

    public Car $car;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
}
