<?php

declare(strict_types = 1);

namespace App\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Create the user from request.
     */
    public function createFromRequest(array $data): User
    {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->password = $this->hashPassword($data['password']);

        return $this->user;
    }

    /**
     * Hash user's password.
     */
    private function hashPassword(string $password): string
    {
        return Hash::make($password);
    }
}
