<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\InspectionNotDeletedException;
use App\Models\Inspection;
use App\Repositories\InspectionRepository;

class DeleteInspectionService
{
    protected Inspection $inspection;
    protected InspectionRepository $repository;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
        $this->repository = new InspectionRepository($this->inspection);
    }

    /**
     * @throws InspectionNotDeletedException
     */
    public function delete(): void
    {
        $this->wipeAttribiutes();
        $this->repository->delete($this->inspection);
    }

    private function wipeAttribiutes(): void
    {
        $this->inspection['end_date'] = null;
    }
}
