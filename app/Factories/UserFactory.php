<?php

declare(strict_types = 1);

namespace App\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    /**
     * Create the user from request.
     */
    public function createFromRequest(array $data): User
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $this->hashPassword($data['password']);

        return $user;
    }

    /**
     * Hash user's password.
     */
    private function hashPassword(string $password): string
    {
        return Hash::make($password);
    }
}
