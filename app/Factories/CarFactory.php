<?php

declare(strict_types = 1);

namespace App\Factories;

use App\Models\Car;

class CarFactory
{
    private Car $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    public function createFromRequest(array $data): Car
    {
        $this->car->owner_id = $data['owner_id'];
        $this->car->brand = $data['brand'];
        $this->car->description = $data['description'];

        return $this->car;
    }
}
