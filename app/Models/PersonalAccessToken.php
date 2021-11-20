<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    public function tokenable()
    {
        return $this->morphTo('tokenable', 'tokenable_type', 'tokenable_uuid');
    }
}
