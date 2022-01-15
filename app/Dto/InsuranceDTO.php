<?php

declare(strict_types = 1);

namespace App\Dto;

use App\Exceptions\MappingDataToObjectException;
use App\Models\Insurance;
use Throwable;

class InsuranceDTO
{
    /**
     * @throws MappingDataToObjectException
     */
    public function mapDataToObject(array $data, Insurance $insurance): Insurance
    {
        try {
            foreach ($data as $key => $value) {
                $insurance[$key] = $data[$key];
            }
        } catch (Throwable) {
            throw new MappingDataToObjectException();
        }

        return $insurance;
    }
}
