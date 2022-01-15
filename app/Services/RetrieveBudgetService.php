<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarBudgetNotFoundException;
use App\Models\EstimatedBudget;
use App\Repositories\BudgetRepository;

class RetrieveBudgetService
{
    protected EstimatedBudget $budget;
    protected BudgetRepository $repository;

    public function __construct(EstimatedBudget $budget)
    {
        $this->budget = $budget;
        $this->repository = new BudgetRepository($this->budget);
    }

    /**
     * @throws CarBudgetNotFoundException
     */
    public function get(): EstimatedBudget
    {
        return $this->repository->get();
    }
}
