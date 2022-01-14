<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\BudgetNotDeletedException;
use App\Models\Car;
use App\Models\EstimatedBudget;
use App\Repositories\BudgetRepository;

class DeleteBudgetService
{
    protected Car $car;
    protected BudgetRepository $repository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new BudgetRepository($this->car);
    }

    /**
     * @throws BudgetNotDeletedException
     */
    public function delete(): void
    {
        $budget = $this->repository->get();
        $budget = $this->clearAllProperties($budget);
        $this->repository->delete($budget);
    }

    public function clearAllProperties($budget): EstimatedBudget
    {
        $budget['original_budget'] = 0;
        $budget['budget_left'] = 0;
        $budget['last_payment_amount'] = null;

        return $budget;
    }
}
