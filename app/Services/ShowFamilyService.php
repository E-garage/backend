<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\FamilyDetailsNotFoundException;
use App\Models\Family;
use App\Repositories\FamilyRepository;

class ShowFamilyService
{
    protected Family $family;
    protected FamilyRepository $repository;

    public function __construct(Family $family)
    {
        $this->family = $family;
        $this->repository = new FamilyRepository($this->family);
    }

    /**
     * @throws FamilyDetailsNotFoundException
     */
    public function show(): Family
    {
        return $this->repository->show();
    }
}
