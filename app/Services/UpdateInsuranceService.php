<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\InsuranceDTO;
use App\Exceptions\InsuranceNotUpdatedException;
use App\Models\Insurance;
use App\Repositories\InsuranceRepository;

class UpdateInsuranceService
{
    protected Insurance $insurance;
    protected InsuranceRepository $repository;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
        $this->repository = new InsuranceRepository($this->insurance);
    }

    /**
     * @throws InsuranceNotUpdatedException
     */
    public function update(array $data): void
    {
        $dto = new InsuranceDTO();
        $this->insurance = $dto->mapDataToObject($data, $this->insurance);
        $this->repository->update($this->insurance);
    }
}
