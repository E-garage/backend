<?php

namespace App\Http\Controllers;

use App\Exceptions\InsuranceNotDeletedException;
use App\Exceptions\InsuranceNotRetrievedFromDatabaseException;
use App\Exceptions\InsuranceNotUpdatedException;
use App\Models\Car;
use App\Services\DeleteInsuranceService;
use App\Services\InsuranceRetrievingService;
use App\Services\UpdateInsuranceService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * @throws InsuranceNotRetrievedFromDatabaseException
     */
    public function get(Car $car): JsonResponse
    {
        $insurance = $car->insurance;

        if (Auth::user()->cannot('view', $insurance)) {
            return new JsonResponse(null, 401);
        }

        $service = new InsuranceRetrievingService($insurance);
        $insurance = $service->get();

        return new JsonResponse(['insurance' => $insurance]);
    }

    /**
     * @throws InsuranceNotUpdatedException
     */
    public function update(Request $request, Car $car): JsonResponse
    {
        $insurance = $car->insurance;

        if (Auth::user()->cannot('update', $insurance)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only('end_date');

        $service = new UpdateInsuranceService($insurance);
        $service->update($data);

        return new JsonResponse();
    }

    /**
     * @throws InsuranceNotDeletedException
     */
    public function delete(Car $car): JsonResponse
    {
        $insurance = $car->insurance;

        if (Auth::user()->cannot('delete', $insurance)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteInsuranceService($insurance);
        $service->delete();

        return new JsonResponse();
    }
}
