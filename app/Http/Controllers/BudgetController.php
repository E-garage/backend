<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Services\DeleteBudgetService;
use App\Services\RetrieveBudgetService;
use App\Services\UpdateBudgetService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function updateOriginalBudget(Request $request, Car $car): JsonResponse
    {
        if (Auth::user()->cannot('update', $car)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only(['original_budget']);

        $service = new UpdateBudgetService($car);
        $service->updateOriginalBudget($data);

        return new JsonResponse();
    }

    public function updateLastPayment(Request $request, Car $car): JsonResponse
    {
        if (Auth::user()->cannot('update', $car)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only(['last_payment_amount']);

        $service = new UpdateBudgetService($car);
        $service->updateLastPayment($data);

        return new JsonResponse();
    }

    public function get(Car $car): JsonResponse
    {
        if (Auth::user()->cannot('view', $car)) {
            return new JsonResponse(null, 401);
        }

        $service = new RetrieveBudgetService($car);
        $budget = $service->get();

        return new JsonResponse(['budget' => $budget]);
    }

    public function delete(Car $car): JsonResponse
    {
        if (Auth::user()->cannot('delete', $car)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteBudgetService($car);
        $service->delete();

        return new JsonResponse();
    }
}
