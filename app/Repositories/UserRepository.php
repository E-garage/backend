<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\UserNotDeletedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotSavedToDatabaseException;
use App\Exceptions\UserNotUpdatedException;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    public function all(): Collection
    {
        return UserModel::all();
    }

    /**
     * @throws UserNotFoundException
     */
    public function findByNameOrEmail(string $nameOrEmail): ?UserModel
    {
        try {
            return UserModel::where('name', $nameOrEmail)->orWhere('email', $nameOrEmail)->first();
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): ?UserModel
    {
        try {
            return UserModel::where('email', $email)->first();
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(string $id): ?UserModel
    {
        try {
            return UserModel::where('id', $id)->first();
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @throws UserNotSavedToDatabaseException
     */
    public function save(UserModel $user): void
    {
        try {
            $user->saveOrFail();
        } catch (\Throwable) {
            throw new UserNotSavedToDatabaseException();
        }
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function update(UserModel $user): void
    {
        try {
            $user->updateOrFail($user->toArray());
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
