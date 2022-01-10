<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Family;
use App\Repositories\FamilyRepository;

class UpdateFamilyService
{
    protected Family $family;
    protected FamilyRepository $repository;

    public function __construct(Family $family)
    {
        $this->family = $family;
        $this->repository = new FamilyRepository($this->family);
    }

    public function updateDetails(array $data): Family
    {
        $this->repository->updateDetails($data);

        return $this->family->refresh();
    }

    public function updateMembers(array $data): Family
    {
        $this->repository->updateMembers($data);

        return $this->family->refresh();
    }

    public function updateCars(array $data): Family
    {
        $this->repository->attachCarsToFamily($data);

        return $this->family->refresh();
    }
}
