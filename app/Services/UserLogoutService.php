<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\UserModel;

class UserLogoutService
{
    public function logout(UserModel $user): void
    {
        /** @phpstan-ignore-next-line */
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }
}
