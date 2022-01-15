<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\BudgetNotDeletedException;
use App\Exceptions\BudgetNotUpdatedException;
use App\Exceptions\CarBudgetNotFoundException;
use App\Models\EstimatedBudget;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetRepository
{
    protected ?EstimatedBudget $budget;

    public function __construct(EstimatedBudget $budget = null)
    {
        $this->budget = $budget;
    }

    /**
     * @throws CarBudgetNotFoundException
     */
    public function get(): EstimatedBudget
    {
        try {
            return $this->budget->refresh();
        } catch (ModelNotFoundException) {
            throw new CarBudgetNotFoundException();
        }
    }

    /**
     * @throws BudgetNotUpdatedException
     */
    public function update(EstimatedBudget $budget)
    {
        try {
            $budget->updateOrFail();
        } catch (\Throwable) {
            throw new BudgetNotUpdatedException();
        }
    }

    /**
     * @throws BudgetNotDeletedException
     */
    public function delete(EstimatedBudget $budget)
    {
        try {
            $this->update($budget);
        } catch (\Throwable) {
            throw new BudgetNotDeletedException();
        }
    }
}
