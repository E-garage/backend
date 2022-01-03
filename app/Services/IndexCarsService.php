<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\CarRepository;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Storage;

class IndexCarsService
{
    protected UserModel $user;
    protected CarRepository $repository;

    public function __construct()
    {
        $this->user = Auth::user(); //@phpstan-ignore-line
        $this->repository = new CarRepository();
    }

    public function index(): Collection
    {
        $cars = $this->repository->index($this->user);
        $cars = $this->getThumbnailsForCars($cars);

        return $cars;
    }

    private function getThumbnailsForCars(Collection $cars): Collection
    {
        foreach ($cars as $car) {
            $filename = $car['thumbnail'];
            $file = Storage::disk('cars_thumbnails')->get($filename);
            $car['thumbnail'] = base64_encode($file);
        }

        return $cars;
    }
}
