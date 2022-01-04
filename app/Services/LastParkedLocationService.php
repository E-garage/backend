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

    public function setLocation($coordinates): void
    {
        $user = Auth::user();
        $this->repository->update($user, $coordinates); //@phpstan-ignore-line
    }

    public function getLocation(): array
    {
        $user = Auth::user();
        $coordinates = $this->repository->get($user); //@phpstan-ignore-line

        return $coordinates;
    }
}
