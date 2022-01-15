<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarNotDeletedFromDatabaseException;
use App\Exceptions\CarsThumbnailNotRemovedFromStorageException;
use App\Models\Car;
use App\Repositories\CarRepository;

class DeleteCarService
{
    protected Car $car;
    protected CarRepository $repository;
    protected CarThumbnailDeletionService $service;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new CarRepository($this->car);
        $this->service = new CarThumbnailDeletionService();
    }

    /**
     * @throws CarsThumbnailNotRemovedFromStorageException
     * @throws CarNotDeletedFromDatabaseException
     */
    public function deleteCar(): void
    {
        $filename = $this->car['thumbnail'];

        $this->repository->delete();
        $this->service->deleteThumbnail($filename);
    }
}
