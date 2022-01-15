<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Models\Car;
use App\Repositories\CarRepository;
use Auth;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Storage;

class IndexCarsService
{
    protected CarRepository $repository;

    public function __construct(Car $car = null)
    {
        $this->repository = new CarRepository($car);
    }

    /**
     * @throws AuthorizedUserNotFoundException
     * @throws FileNotFoundException
     */
    public function index(): Collection
    {
        $user = Auth::user();
        $cars = $this->repository->index($user); //@phpstan-ignore-line

        return $this->getThumbnailsForCars($cars);
    }

    /**
     * @throws \App\Exceptions\CarNotFoundException
     */
    public function findByID(string|int $id): ?Car
    {
        $car = $this->repository->findById($id);

        return $car;
    }

    /**
     * @throws FileNotFoundException
     */
    private function getThumbnailsForCars(Collection $cars): Collection
    {
        foreach ($cars as $car) {
            $filename = $car['thumbnail'];

            if ($filename) {
                $file = Storage::disk('cars_thumbnails')->get($filename);
                $car['thumbnail'] = base64_encode($file);
            }
        }

        return $cars;
    }
}
