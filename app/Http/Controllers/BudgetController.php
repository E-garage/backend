<?php

namespace App\Http\Controllers;

use App\Exceptions\BudgetNotDeletedException;
use App\Exceptions\BudgetNotUpdatedException;
use App\Exceptions\CarBudgetNotFoundException;
use App\Models\EstimatedBudget;
use App\Services\DeleteBudgetService;
use App\Services\RetrieveBudgetService;
use App\Services\UpdateBudgetService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * * @OA\GET(
 *     path="/api/v1/car-budget/{budget_id}/",
 *     tags={"Estimated Budget Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Get estimated budget for given car.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Budget",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(response="500", description="Couldnt find estimated budget for the car."),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/car-budget/{budget_id}/update/original-budget",
 *     tags={"Estimated Budget Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Updated estimated original budget for car.",
 *     @OA\Parameter(
 *     name="budget_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateOriginalBudget"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success update budget"),
 *     @OA\Response(response="422", description="Couldn't update car's budget."),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/car-budget/{budget_id}/update/last-payment",
 *     tags={"Estimated Budget Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Updated last payment amount.",
 *     @OA\Parameter(
 *     name="budget_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateLastPayment"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success update budget"),
 *     @OA\Response(response="422", description="Couldn't update car's budget."),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/car-budget/{budget_id}/delete",
 *     tags={"Estimated Budget Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Delete budget.",
 *     @OA\Parameter(
 *          name="budget_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Success delete budget"),
 *     @OA\Response(response="500", description="Success delete budget"),
 * ),
 */
class BudgetController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateOriginalBudget",
     *             type="object",
     *         @OA\Property(
     *             property="original_budget",
     *             type="numeric|required"
     *         ),
     *         example={
     *              "original_budget": "130",
     *         },
     * )
     */
    /**
     * @throws BudgetNotUpdatedException
     */
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

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateLastPayment",
     *             type="object",
     *         @OA\Property(
     *             property="last_payment_amount",
     *             type="numeric|required"
     *         ),
     *         example={
     *              "last_payment_amount": "130",
     *         },
     * )
     */
    /**
     * @throws BudgetNotUpdatedException
     */
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

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Budget",
     *             type="object",
     *         example={
     *              "budget": {
     *              "id": 2,
     *              "car_id": 2,
     *              "original_budget": "0",
     *              "budget_left": "0",
     *              "last_payment_amount": null,
     *              "created_at": "2022-01-15T03:09:07.000000Z",
     *              "updated_at": "2022-01-15T03:09:07.000000Z",
     *              "car": {
     *                  "id": 2,
     *                  "owner_id": "fd31be72-6928-42e2-b2d4-03985219d77a",
     *                  "brand": "AAA123",
     *                  "description": "Lorem Ipsum",
     *                  "thumbnail": null,
     *                  "created_at": "2022-01-15T03:09:07.000000Z",
     *                  "updated_at": "2022-01-15T03:09:07.000000Z",
     *                  "family_id": null,
     *                  "availability": "available",
     *                  "details": null
     *                  }
     *              }
     *         },
     * )
     */
    /**
     * @throws CarBudgetNotFoundException
     */
    public function get(EstimatedBudget $budget): JsonResponse
    {
        if (Auth::user()->cannot('view', $budget)) {
            return new JsonResponse(null, 401);
        }

        $service = new RetrieveBudgetService($budget);
        $budget = $service->get();

        return new JsonResponse(['budget' => $budget]);
    }

    /**
     * @throws BudgetNotDeletedException
     */
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
