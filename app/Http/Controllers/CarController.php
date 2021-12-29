<?php

namespace App\Http\Controllers;

use App\Factories\CarFactory;
use App\Models\Car;
use App\Services\AddCarService;
use App\Services\AttachThumbnailToCarService;
use App\Services\DeleteCarService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $data = $this->getDataFormRequest($request);
        $thumbnail = $request['thumbnail'];

        $factory = new CarFactory();
        $car = $factory->createFromRequest($data);

        if ($thumbnail) {
            $service = new AttachThumbnailToCarService($car, $thumbnail);
            $car = $service->attachThumbnail();
        }

        $service = new AddCarService($car);
        $service->addCar();

        return new JsonResponse(null, 201);
    }

    public function index()
    {
        // code...
    }

    public function show(Car $car)
    {
        // retrieve car with details
    }

    public function update(Car $car)
    {
        // code...
    }

    public function delete(Car $car): JsonResponse
    {
        if (Auth::user()->cannot('delete', $car)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteCarService($car);
        $service->deleteCar();

        return new JsonResponse();
    }

    private function getDataFormRequest(Request $request): array
    {
        $data = [
            'owner_id' => Auth::user()->id, //@phpstan-ignore-line
            'brand' => $request['brand'],
            'description' => $request['description'],
        ];

        return $data;
    }
}
