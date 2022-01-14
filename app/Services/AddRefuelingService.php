<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Refueling;
use App\Repositories\RefuelingRepository;

class AddRefuelingService
{
    protected Refueling $refueling;
    protected RefuelingRepository $repository;

    public function __construct(Refueling $refueling)
    {
        $this->refueling = $refueling;
        $this->repository = new RefuelingRepository($this->refueling);
    }

    /**
     * @throws \App\Exceptions\RefuelingNotSavedToDatabaseException
     */
    public function addRefueling(): void
    {
        $this->repository->save();
    }
}
