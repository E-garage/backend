<?php

namespace App\Http\Controllers;

use App\Models\EstimatedBudget;
use App\Services\DeleteBudgetService;
use App\Services\RetrieveBudgetService;
use App\Services\UpdateBudgetService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function updateOriginalBudget(Request $request, EstimatedBudget $budget): JsonResponse
    {
        if (Auth::user()->cannot('update', $budget)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only(['original_budget']);

        $service = new UpdateBudgetService($budget);
        $service->updateOriginalBudget($data);

        return new JsonResponse();
    }

    public function updateLastPayment(Request $request, EstimatedBudget $budget): JsonResponse
    {
        if (Auth::user()->cannot('update', $budget)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only(['last_payment_amount']);

        $service = new UpdateBudgetService($budget);
        $service->updateLastPayment($data);

        return new JsonResponse();
    }

    public function get(EstimatedBudget $budget): JsonResponse
    {
        if (Auth::user()->cannot('view', $budget)) {
            return new JsonResponse(null, 401);
        }

        $service = new RetrieveBudgetService($budget);
        $budget = $service->get();

        return new JsonResponse(['budget' => $budget]);
    }

    public function delete(EstimatedBudget $budget): JsonResponse
    {
        if (Auth::user()->cannot('delete', $budget)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteBudgetService($budget);
        $service->delete();

        return new JsonResponse();
    }
}
