<?php

declare(strict_types = 1);

namespace App\Services;

use App\Dto\FamilyDTO;
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
        $this->repository = new FamilyRepository();
    }

    public function updateDetails(array $data): Family
    {
        $this->family = $this->dto->mapDataToObject($data, $this->family);
        $this->repository->updateDetails($this->family);

        return $this->family->refresh();
    }

    public function updateMembers(array $data): Family
    {
        $this->repository->updateMembers($data);

        return $this->family->refresh();
    }

    public function updateCars(array $data): Family
    {
        $this->repository->attachCarsToFamily($data);

        return $this->family->refresh();
    }

    public function detachCar(Car $car): Family
    {
        $car->family_id = null;

        $repository = new CarRepository($car);
        $repository->save();

        return $this->family->refresh();
    }
}
