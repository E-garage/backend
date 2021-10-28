<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Exceptions\UserNotRegisteredException;
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
        $this->credentials = $credentials;
    }

    /**
     * @return User
     * @throws UserNotRegisteredException
     */
    public function register(): User
    {
        $this->setName();
        $this->setEmail();
        $this->hashAndSetPassword();
        $is_created = $this->user->save();

        if(!$is_created)
            throw new UserNotRegisteredException();

        return $this->user;
    }

    /**
     * Set user's name.
     */
    private function setName()
    {
        $this->user->name = $this->credentials['name'];
    }

    /**
     * Set user's email.
     */
    private function setEmail()
    {
        $this->user->email = $this->credentials['email'];
    }

    /**
     * Hash and set user's password.
     */
    private function hashAndSetPassword()
    {
        $this->credentials['password'] = Hash::make($this->credentials['password']);
        $this->user->password = $this->credentials['password'];
    }
}
