<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\InspectionDTO;
use App\Exceptions\InspectionNotUpdatedException;
use App\Models\Inspection;
use App\Repositories\InspectionRepository;

class UpdateInspectionService
{
    protected Inspection $inspection;
    protected InspectionRepository $repository;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
        $this->repository = new InspectionRepository($this->inspection);
    }

    /**
     * @throws InspectionNotUpdatedException
     */
    public function update(array $data): void
    {
        $dto = new InspectionDTO();
        $this->inspection = $dto->mapDataToObject($data, $this->inspection);
        $this->repository->update($this->inspection);
    }
}
