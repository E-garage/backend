<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

class UserObserver
{
    /**
     * Handle the task "saved" event.
     */
    public function saved(User $user): void
    {
        event(new Registered($user));
    }
}
