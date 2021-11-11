<?php

declare(strict_types = 1);

namespace App\Repositories\RepositoryInterfaces;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Find a model by id.
     */
    public function findById(string $id): ?UserModel;

    /**
     * Save a model.
     */
    public function save(): void;

    /**
     * Update a model.
     */
    public function update(UserModel $data): void;

    /**
     * Delete a model by id.
     */
    public function deleteById(int $id): void;
}
