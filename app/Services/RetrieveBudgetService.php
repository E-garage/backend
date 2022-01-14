<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Car;
use App\Models\EstimatedBudget;
use App\Repositories\BudgetRepository;

class RetrieveBudgetService
{
    protected Car $car;
    protected BudgetRepository $repository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new BudgetRepository($this->car);
    }

    /**
     * @throws CarBudgetNotFoundException
     */
    public function get(): EstimatedBudget
    {
        return $this->repository->get();
    }
}
