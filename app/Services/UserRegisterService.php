<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotSavedToDatabaseException;
use App\Models\UserModel;
use App\Repositories\UserRepository;

class UserRegisterService
{
    protected UserModel $user;
    protected UserRepository $repository;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository();
    }

    /**
     * @throws UserNotSavedToDatabaseException
     */
    public function register(): UserModel
    {
        $this->repository->save($this->user);

        return $this->user->refresh();
    }
}
