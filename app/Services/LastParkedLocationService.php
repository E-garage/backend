<?php

declare(strict_types = 1);

namespace App\Services;

use App\Repositories\LastParkedLocationRepository;
use Auth;

class LastParkedLocationService
{
    protected LastParkedLocationRepository $repository;

    public function __construct()
    {
        $this->repository = new LastParkedLocationRepository();
    }

    /**
     * @throws \App\Exceptions\LastParkedLocationNotUpdatedException
     */
    public function setLocation($coordinates): void
    {
        $user = Auth::user();
        $this->repository->update($user, $coordinates); //@phpstan-ignore-line
    }

    /**
     * @throws \App\Exceptions\LastParkedLocationNotRetrievedException
     */
    public function getLocation(): array
    {
        $user = Auth::user();

        return $this->repository->get($user); //@phpstan-ignore-line
    }

    /**
     * @throws \App\Exceptions\LastParkedLocationNotUpdatedException
     */
    public function deleteLocation(): void
    {
        $user = Auth::user();

        $coordinates = [
            'longitude' => null,
            'latitude' => null,
        ];

        $this->repository->update($user, $coordinates); //@phpstan-ignore-line
    }
}
