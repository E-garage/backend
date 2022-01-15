<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\BudgetNotDeletedException;
use App\Models\EstimatedBudget;
use App\Repositories\BudgetRepository;

class DeleteBudgetService
{
    protected EstimatedBudget $budget;
    protected BudgetRepository $repository;

    public function __construct(EstimatedBudget $budget)
    {
        $this->budget = $budget;
        $this->repository = new BudgetRepository($this->budget);
    }

    /**
     * @throws BudgetNotDeletedException
     */
    public function delete(): void
    {
        $this->budget = $this->clearAllProperties($this->budget);
        $this->repository->delete($this->budget);
    }

    public function clearAllProperties($budget): EstimatedBudget
    {
        $budget['original_budget'] = 0;
        $budget['budget_left'] = 0;
        $budget['last_payment_amount'] = null;

        return $budget;
    }
}
