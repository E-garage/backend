<?php

declare(strict_types = 1);

namespace App\Dto;

use App\Exceptions\MappingDataToObjectException;
use App\Models\EstimatedBudget;

class EstimatedBudgetDTO
{
    public function mapDataToObject(array $data, EstimatedBudget $budget): EstimatedBudget
    {
        try {
            foreach ($data as $key => $value) {
                $budget[$key] = $data[$key];
            }

            return $budget;
        } catch (\Throwable) {
            throw new MappingDataToObjectException();
        }
    }
}
