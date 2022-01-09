<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Family;
use App\Repositories\FamilyRepository;

class CreateFamilyService
{
    protected Family $family;
    protected FamilyRepository $repository;

    public function __construct(Family $family)
    {
        $this->family = $family;
        $this->repository = new FamilyRepository($this->family);
    }

    /**
     * @throws FamilyNotSavedToDatabaseException
     */
    public function create(): void
    {
        $this->repository->save();
    }
}
