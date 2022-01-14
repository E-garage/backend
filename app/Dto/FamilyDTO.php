<?php

declare(strict_types = 1);

namespace App\Dto;

use App\Exceptions\MappingDataToObjectException;
use App\Models\Family;
use Throwable;

class FamilyDTO
{
    /**
     * @param array $data
     * @param Family $family
     * @return Family
     * @throws MappingDataToObjectException
     */
    public function mapDataToObject(array $data, Family $family): Family
    {
        try {
            foreach ($data as $key => $value) {
                $family[$key] = $data[$key];
            }
        } catch (Throwable) {
            throw new MappingDataToObjectException();
        }

        return $family;
    }
}
