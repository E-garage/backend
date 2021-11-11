<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\UserNotDeletedException;
use App\Exceptions\UserNotSavedToDatabaseException;
use App\Exceptions\UserNotUpdatedException;
use App\Repositories\RepositoryInterfaces\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements BaseRepository
{
    protected Model $model;

    /**
     * UserRepository constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        try {
            return $this->model->with($relations)->findOrFail($id, $columns);
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
            $this->model->saveOrFail();
        } catch (\Throwable) {
            throw new UserNotSavedToDatabaseException();
        }
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function update(Collection $data): void
    {
        try {
            $this->model->updateOrFail($data->toArray());
        } catch (\Throwable) {
            throw new UserNotUpdatedException();
        }
    }

    /**
     * @throws UserNotDeletedException
     */
    public function deleteById(int $id): void
    {
        try {
            $this->findById($id)->deleteOrFail();
        } catch (\Throwable) {
            throw new UserNotDeletedException();
        }
    }
}
