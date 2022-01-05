<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotUpdatedException;
use App\Repositories\UserRepository;
use Auth;
use Hash;

class UserAccountManagementService
{
    protected UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function updatePassword(string $newPassword): void
    {
        $user = Auth::user();
        $hashedPassword = Hash::make($newPassword);
        $user['password'] = $hashedPassword;
        $this->repository->update($user); //@phpstan-ignore-line
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function updateEmail(string $newEmail): void
    {
        $user = Auth::user();
        $user['email'] = $newEmail;
        $this->repository->update($user); //@phpstan-ignore-line
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function updateName(string $newName): void
    {
        $user = Auth::user();
        $user['name'] = $newName;
        $this->repository->update($user); //@phpstan-ignore-line
    }
}
