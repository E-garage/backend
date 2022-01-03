<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Car;
use App\Repositories\CarRepository;

class UpdateCarService
{
    protected Car $car;
    protected array $data;
    protected CarRepository $respository;

    public function __construct(Car $car, array $data = [])
    {
        $this->car = $car;
        $this->data = $data;
        $this->respository = new CarRepository($this->car);
    }

    public function update(): void
    {
        $this->respository->update($this->data);
    }
}
