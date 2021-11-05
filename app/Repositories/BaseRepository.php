<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\RepositoryInterfaces\BaseRepository as BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    /**
     * BaseRepository constructor.
     */
    protected function __construct(Model $model)
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

    public function save(Collection $data): bool
    {
        try {
            return $this->model->saveOrFail($data->toArray());
        } catch (Throwable) {
            return false;
        }
    }

    public function update(Collection $data): bool
    {
        try {
            return $this->model->updateOrFail($data->toArray());
        } catch (Throwable) {
            return false;
        }
    }

    public function deleteById(int $id): bool
    {
        try {
            return $this->findById($id)->deleteOrFail();
        } catch (Throwable) {
            return false;
        }
    }
}
