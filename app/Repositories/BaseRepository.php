<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\RepositoryInterfaces\BaseRepository as BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->model->with($relations)->findOrFail($id, $columns);
    }

    public function findTrashedById(int $id): ?Model
    {
        if($this->isModelUsingSoftDeletes()) {
            return $this->model->onlyTrashed()->findOrFail($id);
        }
    }

    public function save(Collection $data): bool
    {
        return $this->model->saveOrFail($data->toArray());
    }

    public function update(Collection $data): bool
    {
        return $this->model->update($data->toArray());
    }

    public function deleteById(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function restoreById(int $id): bool
    {
        if($this->isModelUsingSoftDeletes()) {
            return $this->findTrashedById($id)->restore();
        }
    }

    public function permamentlyDeleteById(int $id): bool
    {
        if($this->isModelUsingSoftDeletes()) {
            return $this->findTrashedById($id)->forceDelete();
        }
    }

    private function isModelUsingSoftDeletes(): bool
    {
        $traits_used_by_model = class_uses_recursive($this->model);
        return in_array(SoftDeletes::class, $traits_used_by_model);
    }
}
