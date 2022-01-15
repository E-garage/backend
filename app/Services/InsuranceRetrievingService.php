<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\InsuranceNotRetrievedFromDatabaseException;
use App\Models\Insurance;
use App\Repositories\InsuranceRepository;

class InsuranceRetrievingService
{
    protected Insurance $insurance;
    protected InsuranceRepository $repository;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
        $this->repository = new InsuranceRepository($this->insurance);
    }

    /**
     * @throws InsuranceNotRetrievedFromDatabaseException
     */
    public function get(): Insurance
    {
        return $this->repository->get();
    }
}
