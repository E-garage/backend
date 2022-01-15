<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarNotUpdatedException;
use App\Models\Car;
use App\Repositories\CarRepository;

class UpdateCarService
{
    protected Car $car;
    protected array $data;
    protected CarRepository $repository;

    public function __construct(Car $car, array $data = [])
    {
        $this->car = $car;
        $this->data = $data;
        $this->repository = new CarRepository($this->car);
    }

    /**
     * @throws CarNotUpdatedException
     */
    public function update(): void
    {
        $this->repository->update($this->data);
    }
}
