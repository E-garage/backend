<?php

namespace App\Http\Controllers;

use App\Exceptions\InspectionNotDeletedException;
use App\Exceptions\InspectionNotUpdatedException;
use App\Models\Car;
use App\Services\DeleteInspectionService;
use App\Services\InspectionRetrievingService;
use App\Services\UpdateInspectionService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function get(Car $car): JsonResponse
    {
        $inspection = $car->inspection;

        if (Auth::user()->cannot('view', $inspection)) {
            return new JsonResponse(null, 401);
        }

        $service = new InspectionRetrievingService($inspection);
        $inspection = $service->get();

        return new JsonResponse(['inspection' => $inspection]);
    }

    /**
     * @throws InspectionNotUpdatedException
     */
    public function update(Request $request, Car $car): JsonResponse
    {
        $inspection = $car->inspection;

        if (Auth::user()->cannot('update', $inspection)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only('end_date');

        $service = new UpdateInspectionService($inspection);
        $service->update($data);

        return new JsonResponse();
    }

    /**
     * @throws InspectionNotDeletedException
     */
    public function delete(Car $car): JsonResponse
    {
        $inspection = $car->inspection;

        if (Auth::user()->cannot('delete', $inspection)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteInspectionService($inspection);
        $service->delete();

        return new JsonResponse();
    }
}
