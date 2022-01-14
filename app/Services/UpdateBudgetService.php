<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\EstimatedBudgetDTO;
use App\Exceptions\BudgetNotUpdatedException;
use App\Models\Car;
use App\Repositories\BudgetRepository;

class UpdateBudgetService
{
    protected Car $car;
    protected BudgetRepository $repository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new BudgetRepository($this->car);
    }

    /**
     * @throws BudgetNotUpdatedException
     */
    public function updateOriginalBudget(array $data): void
    {
        $dto = new EstimatedBudgetDTO();
        $budget = $this->repository->get();
        $budget = $dto->mapDataToObject($data, $budget);
        $this->repository->update($budget);
    }

    /**
     * @throws BudgetNotUpdatedException
     */
    public function updateLastPayment(array $data): void
    {
        $dto = new EstimatedBudgetDTO();
        $budget = $this->repository->get();
        $budget = $dto->mapDataToObject($data, $budget);
        $this->repository->update($budget);
    }
}
