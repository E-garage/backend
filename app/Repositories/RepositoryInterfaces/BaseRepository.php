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
     * Save a model.
     */
    public function save(): void;

    /**
     * Update a model.
     */
    public function update(Collection $data): void;

    /**
     * Delete a model by id.
     */
    public function deleteById(int $id): void;
}
