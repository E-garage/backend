<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotRegisteredException;
use App\Models\User;
use App\Repositories\UserRepository;

/**
 * Service for registering users.
 */
class UserRegisterService
{
    protected User $user;

    protected UserRepository $repository;

    /**
     * UserRegisterService constructor.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Register a user.
     *
     * @throws UserNotRegisteredException
     */
    public function register(): User
    {
        $created = $this->repository->save();

        if (!$created) {
            throw new UserNotRegisteredException();
        }

        return $this->user;
    }
}
