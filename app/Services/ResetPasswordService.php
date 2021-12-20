<?php

declare(strict_types = 1);

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Str;

class ResetPasswordService
{
    public function reset(array $data): bool
    {
        $status = Password::reset(
            $data,
            function ($user, $password)
            {
                $user['password'] = Hash::make($password);
                $user->setRememberToken(Str::random(60));

                $repository = new UserRepository($user);
                $repository->update($user);

                event(new PasswordReset($user));
            });

        return $this->isReseted($status);
    }

    private function isReseted(string $status): bool
    {
        return $status === Password::PASSWORD_RESET;
    }
}
