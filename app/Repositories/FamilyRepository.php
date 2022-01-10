<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Exceptions\CarNotAttachedToFamilyException;
use App\Exceptions\FamilyDetailsNotFoundException;
use App\Exceptions\FamilyNotDeletedException;
use App\Exceptions\FamilyNotSavedToDatabaseException;
use App\Exceptions\FamilyNotUpdatedException;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;

class FamilyRepository
{
    protected ?Family $family;

    public function __construct(Family $family = null)
    {
        $this->family = $family;
    }

    /**
     * @throws AuthorizedUserNotFoundException
     */
    public function index(UserModel $user): Collection
    {
        try {
            $families = $user->families()->get();
            $createdFamilies = $user->createdFamilies()->get();

            return $families->add($createdFamilies);
        } catch (\Throwable) {
            throw new AuthorizedUserNotFoundException();
        }
    }

    /**
     * @throws FamilyDetailsNotFoundException
     */
    public function show(): Collection
    {
        try {
            return $this->family->with('members', 'cars')->get();
        } catch (\Throwable) {
            throw new FamilyDetailsNotFoundException();
        }
    }

    /**
     * @throws FamilyNotSavedToDatabaseException
     */
    public function save(): void
    {
        try {
            $this->family->saveOrFail();
        } catch (\Throwable) {
            throw new FamilyNotSavedToDatabaseException();
        }
    }

    public function updateDetails(array $data): void
    {
        try {
            $this->family->updateOrFail($data);
        } catch (\Throwable) {
            throw new FamilyNotUpdatedException();
        }
    }

    /**
     * @throws FamilyNotUpdatedException
     */
    public function updateMembers(array $data): void
    {
        try {
            $repository = new UserRepository();

            foreach ($data as $nameOrEmail) {
                $user = $repository->findByNameOrEmail($nameOrEmail);

                if ($user) {
                    $this->family->members()->toggle($user->id);
                }
            }
        } catch (\Throwable) {
            throw new FamilyNotUpdatedException();
        }
    }

    /**
     * @throws CarNotAttachedToFamilyException
     */
    public function attachCarsToFamily(array $data): void
    {
        try {
            $repository = new CarRepository();

            foreach ($data as $carId) {
                $car = $repository->findById($carId);

                if ($car) {
                    $car->family_id = $this->family->id;
                    $car->save();
                }
            }
        } catch (\Throwable) {
            throw new CarNotAttachedToFamilyException();
        }
    }

    /**
     * @throws FamilyNotDeletedException
     */
    public function delete(): void
    {
        try {
            $this->family->members()->detach();
            $this->family->deleteOrFail();
        } catch (\Throwable) {
            throw new FamilyNotDeletedException();
        }
    }
}
