<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotUpdatedException;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Hash;

/**
 * Service for managing user's account.
 */
class UserAccountManagementService
{
    protected UserModel $user;
    protected UserRepository $repository;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Updates user's account password.
     */
    public function updatePassword(string $newPassword): void
    {
        $hashedPassword = Hash::make($newPassword);
        $this->user['password'] = $hashedPassword;
        $this->repository->update($this->user);
    }

    /**
     * Updates user's account email.
     *
     * @throws UserNotUpdatedException
     */
    public function updateEmail(string $newEmail): void
    {
        $this->user['email'] = $newEmail;
        $this->repository->update($this->user);
    }

    /**
     * Updates user's account name.
     *
     * @throws UserNotUpdatedException
     */
    public function updateName(string $newName): void
    {
        $this->user['name'] = $newName;
        $this->repository->update($this->user);
    }
}
