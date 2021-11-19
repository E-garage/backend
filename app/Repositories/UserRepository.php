<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\UserNotDeletedException;
use App\Exceptions\UserNotSavedToDatabaseException;
use App\Exceptions\UserNotUpdatedException;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    protected UserModel $userModel;

    /**
     * UserRepository constructor.
     */
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function all(): Collection
    {
        return UserModel::all();
    }
    public function findByEmail(string $email): ?UserModel
    {
        try {
            return $this->userModel::Where('email', $email)->first();
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function findById(string $id): ?UserModel
    {
        try {
            return $this->userModel::where('id', $id)->first();
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    /**
     * @throws UserNotSavedToDatabaseException
     */
    public function save(): void
    {
        try {
            $this->userModel->saveOrFail();
        } catch (\Throwable) {
            throw new UserNotSavedToDatabaseException();
        }
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function update(UserModel $userModel): void
    {
        try {
            $userModel->updateOrFail($userModel->toArray());
        } catch (\Throwable) {
            throw new UserNotUpdatedException();
        }
    }

    /**
     * @throws UserNotDeletedException
     */
    public function deleteById(string $id): void
    {
        try {
            $this->findById($id)->deleteOrFail();
        } catch (\Throwable) {
            throw new UserNotDeletedException();
        }
    }
}
