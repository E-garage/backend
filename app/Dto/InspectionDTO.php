<?php

declare(strict_types = 1);

namespace App\Dto;

use App\Exceptions\MappingDataToObjectException;
use App\Models\Inspection;
use Throwable;

class InspectionDTO
{
    /**
     * @throws MappingDataToObjectException
     */
    public function mapDataToObject(array $data, Inspection $inspection): Inspection
    {
        try {
            foreach ($data as $key => $value) {
                $inspection[$key] = $data[$key];
            }
        } catch (Throwable) {
            throw new MappingDataToObjectException();
        }

        return $inspection;
    }
}
