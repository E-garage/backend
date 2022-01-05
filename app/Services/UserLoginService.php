<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UserCredentialsInvalidExecption;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserLoginService
{
    protected UserModel $user;

    protected UserRepository $repository;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository();
    }

    /**
     * @throws UserCredentialsInvalidExecption
     */
    public function login(): UserModel
    {
        try {
            $user = $this->repository->findByEmail($this->user->email);

            if (Hash::check($this->user->password, $user->password)) {
                return $user;
            } else {
                throw new UserCredentialsInvalidExecption();
            }
        } catch (ModelNotFoundException) {
            throw new UserCredentialsInvalidExecption();
        }
    }
}
