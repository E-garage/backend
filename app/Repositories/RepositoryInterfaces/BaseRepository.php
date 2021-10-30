<?php

declare(strict_types=1);

namespace App\Repositories\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * Get all models.
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find a model by id.
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return null|Model
     */
    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Find a trashed model by id.
     * @param int $id
     * @return null|Model
     */
    public function findTrashedById(int $id): ?Model;

    /**
     * Create a model.
     * @param Collection $data
     * @return null|Model
     */
    public function create(Collection $data): ?Model;

    /**
     * Update a model.
     * @param Collection $data
     * @return bool
     */
    public function update(Collection $data): bool;

    /**
     * Delete a model by id.
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * Restore a model by id.
     * @param int $id
     * @return bool
     */
    public function restoreById(int $id): bool;

    /**
     * Permamently delete a model by id.
     * @param int $id
     * @return bool
     */
    public function permamentlyDeleteById(int $id): bool;
}
