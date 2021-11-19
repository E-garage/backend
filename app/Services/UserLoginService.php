<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserCredentialsInvalidExecption;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotSavedToDatabaseException;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Service for login users.
 */
class UserLoginService
{
    protected UserModel $user;

    protected UserRepository $repository;

    /**
     * UserLoginService constructor.
     */
    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Login a user.
     *
     * @throws UserNotSavedToDatabaseException
     */
    public function login(): UserModel
    {
        if($this->repository->findByEmail($this->user->email)){
            $this->checkpassword();
            return $this->user;
        } else {
            throw new UserNotFoundException();
        }
    }
    private function checkpassword(): UserModel
    {
        if (Hash::check($this->user->password, $this->repository->findByEmail($this->user->email)->getAttribute('password'))) {
            return $this->user;
        }else {
            throw new UserCredentialsInvalidExecption();
        }
    }
    /**
    logout a user
    **/
    public function logout() : UserModel
    {

    }
}
