<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Exceptions\RefuelingNotDeletedFromDatabaseException;
use App\Exceptions\RefuelingNotSavedToDatabaseException;
use App\Exceptions\RefuelingNotUpdatedException;
use App\Models\Refueling;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class RefuelingRepository
{
    protected ?Refueling $refueling;

    public function __construct(Refueling $refueling = null)
    {
        $this->refueling = $refueling;
    }

    /**
     * @throws AuthorizedUserNotFoundException
     */
    public function index(UserModel $user): Collection
    {
        try {
            return $user->refueling()->get(['id','car_id','date','FuelType','TotalPrice','amount']);
        } catch (\Throwable) {
            throw new AuthorizedUserNotFoundException();
        }
    }

    public function findById(string $id): Collection
    {
        try {
            return Refueling::where('id', $id)->get();
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    /**
     * @throws RefuelingNotSavedToDatabaseException
     */
    public function save(): void
    {
        $this->refueling->saveOrFail();
    }

    /**
     * @throws RefuelingNotUpdatedException
     */
    public function update(array $data): void
    {
        try {
            $this->refueling->updateOrFail($data);
        } catch (\Throwable) {
            throw new RefuelingNotUpdatedException();
        }
    }

    /**
     * @throws RefuelingNotDeletedFromDatabaseException
     */
    public function delete(): void
    {
        try {
            $this->refueling->deleteOrFail();
        } catch (\Throwable) {
            throw new RefuelingNotDeletedFromDatabaseException();
        }
    }
}
