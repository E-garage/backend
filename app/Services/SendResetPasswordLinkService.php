<?php

declare(strict_types = 1);

namespace App\Services;

use Illuminate\Support\Facades\Password;

class SendResetPasswordLinkService
{
    public function sendLink(array $email): bool
    {
        $status = Password::sendResetLink($email);
        $isSent = $this->isSent($status);

        return $isSent;
    }

    private function isSent(string $status): bool
    {
        return $status === Password::RESET_LINK_SENT;
    }
}
