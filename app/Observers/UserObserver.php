<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\UserModel;
use Illuminate\Auth\Events\Registered;

class UserObserver
{
    /**
     * Handle the task "saved" event.
     */
    public function saved(UserModel $user): void
    {
        event(new Registered($user));
    }
}
