<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Car;
use App\Repositories\CarRepository;

class AddCarService
{
    protected Car $car;
    protected CarRepository $respository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->respository = new CarRepository($this->car);
    }

    public function addCar(): void
    {
        $this->respository->save();
    }
}
