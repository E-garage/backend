<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Exceptions\CarNotDeletedFromDatabaseException;
use App\Exceptions\CarNotSaveToDatabaseException;
use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CarRepository
{
    protected ?Car $car;

    public function __construct(Car $car = null)
    {
        $this->car = $car;
    }

    /**
     * @throws AuthorizedUserNotFoundException
     */
    public function index(UserModel $user): Collection
    {
        try {
            return $user->cars()->get();
        } catch (\Throwable) {
            throw new AuthorizedUserNotFoundException();
        }
    }

    public function findById(string $id): Collection|Car|null
    {
        try {
            return Car::where('id', $id)->get();
        } catch (ModelNotFoundException) {
            return null;
        }
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

    /**
     * @throws CarNotDeletedFromDatabaseException
     */
    public function delete(): void
    {
        try {
            $this->car->deleteOrFail();
        } catch (\Throwable) {
            throw new CarNotDeletedFromDatabaseException();
        }
    }
}
