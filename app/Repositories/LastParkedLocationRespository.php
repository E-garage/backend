<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\LastParkedLocationNotRetrievedException;
use App\Exceptions\LastParkedLocationNotUpdatedException;
use App\Models\UserModel;

class LastParkedLocationRepository
{
    public function update(UserModel $user, array $coordinates): void
    {
        try {
            $location = $user->location;
            $location->updateOrFail($coordinates);
        } catch (\Throwable) {
            throw new LastParkedLocationNotUpdatedException();
        }
    }

    public function get(UserModel $user): array
    {
        try {
            $location = $user->location;

            return [
                'longitude' => $location['longitude'],
                'latitude' => $location['latitude'],
            ];
        } catch (\Throwable) {
            throw new LastParkedLocationNotRetrievedException();
        }
    }
}
