<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarsThumbnailNotRemovedFromStorage;
use App\Models\Car;
use App\Repositories\CarRepository;
use Storage;

class DeleteCarService
{
    protected Car $car;
    protected CarRepository $repository;

    public function __construct(Car $car)
    {
        $this->car = $car;
        $this->repository = new CarRepository($this->car);
    }

    public function deleteCar(): void
    {
        $this->repository->delete();
        $this->removeThumbnailFromStorage();

        return;
    }

    /**
     * Removes car's thumbnail from storage if exists.
     *
     * @throws CarsThumbnailNotRemovedFromStorage
     */
    private function removeThumbnailFromStorage(): void
    {
        $filename = $this->car['thumbnail'];

        if (!$filename) {
            return;
        }

        $success = Storage::disk('cars_thumbnails')->delete($filename);

        if (!$success) {
            throw new CarsThumbnailNotRemovedFromStorage();
        }
    }
}
