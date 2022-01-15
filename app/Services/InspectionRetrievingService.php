<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Inspection;
use App\Repositories\InspectionRepository;

class InspectionRetrievingService
{
    protected Inspection $inspection;
    protected InspectionRepository $repository;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
        $this->repository = new InspectionRepository($this->inspection);
    }

    public function get(): Inspection
    {
        return $this->repository->get();
    }
}
