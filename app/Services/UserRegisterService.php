<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotSavedToDatabaseException;
use App\Models\UserModel;
use App\Repositories\UserRepository;

/**
 * Service for registering users.
 */
class UserRegisterService
{
    protected UserModel $user;

    protected UserRepository $repository;

    /**
     * UserRegisterService constructor.
     */
    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Register a user.
     *
     * @throws UserNotSavedToDatabaseException
     */
    public function register(): UserModel
    {
        $this->repository->save();

        return $this->user;
    }
}
