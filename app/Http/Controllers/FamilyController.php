<?php

namespace App\Http\Controllers;

use App\Factories\FamilyFactory;
use App\Models\Car;
use App\Models\Family;
use App\Services\CreateFamilyService;
use App\Services\DeleteFamilyService;
use App\Services\IndexFamiliesService;
use App\Services\ShowFamilyService;
use App\Services\UpdateFamilyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function get(): JsonResponse
    {
        $service = new IndexFamiliesService();
        $families = $service->index();

        return new JsonResponse($families);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'description']);

        $factory = new FamilyFactory();
        $family = $factory->createFormRequest($data);

        $service = new CreateFamilyService($family);
        $service->create();

        return new JsonResponse(null, 201);
    }

    public function show(Family $family): JsonResponse
    {
        $service = new ShowFamilyService($family);
        $family = $service->show();

        return new JsonResponse(['family' => $family]);
    }

    public function updateDetails(Request $request, Family $family): JsonResponse
    {
        $data = $request->only(['name', 'description']);

        $service = new UpdateFamilyService($family);
        $family = $service->updateDetails($data);

        return new JsonResponse($family);
    }

    public function updateMembers(Request $request, Family $family): JsonResponse
    {
        $data = $request->get('names') ?? $request->get('emails');

        $service = new UpdateFamilyService($family);
        $family = $service->updateMembers($data);

        return new JsonResponse($family);
    }

    public function updateCars(Request $request, Family $family): JsonResponse
    {
        $data = $request->get('cars');

        $service = new UpdateFamilyService($family);
        $service->updateCars($data);

        return new JsonResponse();
    }

    public function detachCar(Family $family, Car $car): JsonResponse
    {
        $service = new UpdateFamilyService($family);
        $service->detachCar($car);

        return new JsonResponse();
    }

    public function delete(Family $family): JsonResponse
    {
        $service = new DeleteFamilyService($family);
        $service->delete();

        return new JsonResponse();
    }
}
