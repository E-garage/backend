<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\EstimatedBudgetDTO;
use App\Exceptions\BudgetNotUpdatedException;
use App\Models\EstimatedBudget;
use App\Repositories\BudgetRepository;

class UpdateBudgetService
{
    protected EstimatedBudget $budget;
    protected BudgetRepository $repository;

    public function __construct(EstimatedBudget $budget)
    {
        $this->budget = $budget;
        $this->repository = new BudgetRepository($this->budget);
    }

    /**
     * @throws BudgetNotUpdatedException
     */
    public function updateOriginalBudget(array $data): void
    {
        $dto = new EstimatedBudgetDTO();

        $this->budget = $dto->mapDataToObject($data, $this->budget);
        $this->repository->update($this->budget);
    }

    /**
     * @throws BudgetNotUpdatedException
     */
    public function updateLastPayment(array $data): void
    {
        $dto = new EstimatedBudgetDTO();

        $this->budget = $dto->mapDataToObject($data, $this->budget);
        $this->repository->update($this->budget);
    }
}
