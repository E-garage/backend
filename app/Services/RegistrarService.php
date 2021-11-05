<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserNotRegisteredException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * Service for registering users.
 */
class RegistrarService
{
    protected User $user;

    protected UserRepository $repository;

    protected Collection $credentials;

    /**
     * RegistrarService constructor.
     */
    public function __construct(Collection $credentials)
    {
        $this->user = new User();
        $this->repository = new UserRepository($this->user);
        $this->credentials = $credentials;
    }

    /**
     * Register a user.
     *
     * @throws UserNotRegisteredException
     */
    public function register(): User
    {
        $this->hashPassword();
        $created = $this->repository->save($this->credentials);

        if (empty($created)) {
            throw new UserNotRegisteredException();
        }

        return $this->user;
    }

    /**
     * Hash user's password.
     */
    private function hashPassword()
    {
        $this->credentials['password'] = Hash::make($this->credentials['password']);
    }
}
