<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Car;
use App\Repositories\CarRepository;

class AddCarService
{
    protected Car $car;
    protected CarRepository $repository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new CarRepository($this->car);
    }

    /**
     * @throws \App\Exceptions\CarNotSavedToDatabaseException
     */
    public function addCar(): void
    {
        $this->repository->save();
    }
}
