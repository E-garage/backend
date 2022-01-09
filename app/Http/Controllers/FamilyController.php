<?php

namespace App\Http\Controllers;

use App\Factories\FamilyFactory;
use App\Models\Family;
use App\Services\CreateFamilyService;
use App\Services\IndexFamiliesService;
use App\Services\ShowFamilyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function get(): JsonResponse
    {
        $service = new IndexFamiliesService();
        $families = $service->index();

        return new JsonResponse(['families' => $families]);
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

    public function updateDetails(Request $request, Family $family)
    {
    }

    public function updateMembers(Request $request, Family $family)
    {
    }

    public function delete(Family $family)
    {
    }
}
