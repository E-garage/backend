<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Exceptions\UserNotRegisteredException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
* Service for registering users.
* @package App\Services
*/
class RegistrarService
{
    /**
     * @var User $user
     */
    protected User $user;

    /**
     * @var UserRepository $repository
     */
    protected UserRepository $repository;

    /**
     * @var Collection $credentials
     */
    protected Collection $credentials;

    /**
     * RegistrarService constructor.
     * @param Collection $credentials
     */
    public function __construct(Collection $credentials)
    {
        $this->user = new User();
        $this->repository = new UserRepository($this->user);
        $this->credentials = $credentials;
    }

    /**
     * Register a user.
     * @return User
     * @throws UserNotRegisteredException
     */
    public function register(): User
    {
        $this->hashPassword();
        $this->user = $this->repository->create($this->credentials);

        if(empty($this->user))
            throw new UserNotRegisteredException();

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
