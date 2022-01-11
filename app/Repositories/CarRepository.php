<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Exceptions\CarNotDeletedFromDatabaseException;
use App\Exceptions\CarNotSavedToDatabaseException;
use App\Exceptions\CarNotUpdatedException;
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
            return $user->cars()->get(['id', 'brand', 'description', 'thumbnail']);
        } catch (\Throwable) {
            throw new AuthorizedUserNotFoundException();
        }
    }

    public function findById(string|int $id): ?Car
    {
        try {
            return Car::where('id', $id)->first();
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    /**
     * @throws CarNotSavedToDatabaseException
     */
    public function save(): void
    {
        try {
            $this->car->saveOrFail();
        } catch (\Throwable) {
            throw new CarNotSavedToDatabaseException();
        }
    }

    /**
     * @throws CarNotUpdatedException
     */
    public function update(array $data): void
    {
        try {
            $this->car->updateOrFail($data);
        } catch (\Throwable) {
            throw new CarNotUpdatedException();
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
