<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterfaces\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements BaseRepository
{
    protected User $model;

    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id, array $columns = ['*'], array $relations = []): ?User
    {
        try {
            return $this->model->with($relations)->findOrFail($id, $columns);
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function save(): bool
    {
        try {
            return $this->model->saveOrFail();
        } catch (\Throwable) {
            return false;
        }
    }

    public function update(Collection $data): bool
    {
        try {
            return $this->model->updateOrFail($data->toArray());
        } catch (\Throwable) {
            return false;
        }
    }

    public function deleteById(int $id): bool
    {
        try {
            return $this->findById($id)->deleteOrFail();
        } catch (\Throwable) {
            return false;
        }
    }
}
