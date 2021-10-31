<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected User $model;

    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
