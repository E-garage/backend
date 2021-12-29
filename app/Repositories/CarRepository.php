<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\CarNotDeletedFromDatabaseException;
use App\Exceptions\CarNotSaveToDatabaseException;
use App\Models\Car;

class CarRepository
{
    protected Car $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @throws CarNotSaveToDatabaseException
     */
    public function save(): void
    {
        try {
            $this->car->saveOrFail();
        } catch (\Throwable) {
            throw new CarNotSaveToDatabaseException();
        }
    }

    public function delete(): void
    {
        try {
            $this->car->deleteOrFail();
        } catch (\Throwable) {
           throw new CarNotDeletedFromDatabaseException();
        }
    }
}
