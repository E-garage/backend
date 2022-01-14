<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\BudgetNotDeletedException;
use App\Exceptions\BudgetNotUpdatedException;
use App\Exceptions\CarBudgetNotFoundException;
use App\Models\Car;
use App\Models\EstimatedBudget;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BudgetRepository
{
    protected ?Car $car;

    public function __construct(Car $car = null)
    {
        $this->car = $car;
    }

    /**
     * @throws CarBudgetNotFoundException
     */
    public function get(): EstimatedBudget
    {
        try {
            return $this->car->budget;
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
