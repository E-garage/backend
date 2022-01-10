<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Family;
use App\Repositories\FamilyRepository;

class DeleteFamilyService
{
    protected Family $family;
    protected FamilyRepository $repository;

    public function __construct(Family $family)
    {
        $this->family = $family;
        $this->repository = new FamilyRepository($this->family);
    }

    public function delete(): void
    {
        $this->repository->delete();
    }
}
