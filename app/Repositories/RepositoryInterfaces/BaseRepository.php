<?php

declare(strict_types = 1);

namespace App\Repositories\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Find a model by id.
     */
    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Find a trashed model by id.
     */
    public function findTrashedById(int $id): ?Model;

    /**
     * Save a model.
     */
    public function save(Collection $data): bool;

    /**
     * Update a model.
     */
    public function update(Collection $data): bool;

    /**
     * Delete a model by id.
     */
    public function deleteById(int $id): bool;

    /**
     * Restore a model by id.
     */
    public function restoreById(int $id): bool;

    /**
     * Permamently delete a model by id.
     */
    public function permamentlyDeleteById(int $id): bool;
}
