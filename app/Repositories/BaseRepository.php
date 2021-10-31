<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\RepositoryInterfaces\BaseRepository as BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
        return $this->model->select($columns)->with($relations)->findOrFail($id);
    }

    public function findTrashedById(int $id): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($id);
    }

    public function create(Collection $data): ?Model
    {
        return $this->model->create($data->toArray());
    }

    public function update(Collection $data): bool
    {
        return $this->model->update($data->toArray());
    }

    public function deleteById(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function restoreById(int $id): bool
    {
        return $this->findTrashedById($id)->restore();
    }

    public function permamentlyDeleteById(int $id): bool
    {
        return $this->model->forceDelete();
    }
}
