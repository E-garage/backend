<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\FamilyDTO;
use App\Exceptions\CarNotAttachedToFamilyException;
use App\Exceptions\CarNotSavedToDatabaseException;
use App\Exceptions\FamilyNotUpdatedException;
use App\Exceptions\MappingDataToObjectException;
use App\Models\Car;
use App\Models\Family;
use App\Repositories\CarRepository;
use App\Repositories\FamilyRepository;

class UpdateFamilyService
{
    protected Family $family;
    protected FamilyDTO $dto;
    protected FamilyRepository $repository;

    public function __construct(Family $family)
    {
        $this->family = $family;
        $this->dto = new FamilyDTO();
        $this->repository = new FamilyRepository($this->family);
    }

    /**
     * @throws FamilyNotUpdatedException
     * @throws MappingDataToObjectException
     */
    public function updateDetails(array $data): Family
    {
        $this->family = $this->dto->mapDataToObject($data, $this->family);
        $this->repository->updateDetails($this->family);

        return $this->family->refresh();
    }

    /**
     * @throws FamilyNotUpdatedException
     */
    public function updateMembers(array $data): Family
    {
        $this->repository->updateMembers($data);

        return $this->family->refresh();
    }

    /**
     * @throws CarNotAttachedToFamilyException
     */
    public function updateCars(array $data): Family
    {
        $this->repository->attachCarsToFamily($data);

        return $this->family->refresh();
    }

    /**
     * @throws CarNotSavedToDatabaseException
     */
    public function detachCar(Car $car): Family
    {
        $car->family_id = null;

        $repository = new CarRepository($car);
        $repository->save();

        return $this->family->refresh();
    }
}
