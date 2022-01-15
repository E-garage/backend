<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\InsuranceNotDeletedException;
use App\Models\Insurance;
use App\Repositories\InsuranceRepository;

class DeleteInsuranceService
{
    protected Insurance $insurance;
    protected InsuranceRepository $repository;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
        $this->repository = new InsuranceRepository($this->insurance);
    }

    /**
     * @throws InsuranceNotDeletedException
     */
    public function delete(): void
    {
        $this->wipeAttribiutes();
        $this->repository->delete($this->insurance);
    }

    private function wipeAttribiutes(): void
    {
        $this->insurance['end_date'] = null;
    }
}
