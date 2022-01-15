<?php

namespace App\Http\Controllers;

use App\Exceptions\InspectionNotDeletedException;
use App\Exceptions\InspectionNotRetrievedFromDatabaseException;
use App\Exceptions\InspectionNotUpdatedException;
use App\Models\Car;
use App\Services\DeleteInspectionService;
use App\Services\InspectionRetrievingService;
use App\Services\UpdateInspectionService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * * @OA\GET(
 *     path="/api/v1/cars/inspection/{car_id}",
 *     tags={"Car's Inspection Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Get the expire date of car's inspection for the given car.",
 *     @OA\Parameter(
 *     name="car_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Inspection",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(response="404", description="Couldn't retrieve inspection."),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/cars/inspection/{car_id}/update",
 *     tags={"Car's Inspection Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Set the expire date of car's inspection for the given car.",
 *     @OA\Parameter(
 *     name="car_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateInspection"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success update inspection"),
 *     @OA\Response(response="422", description="Couldn't update inspection."),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/cars/inspection/{car_id}/delete",
 *     tags={"Car's Inspection Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Delete the expire date of car's inspection for the given car.",
 *     @OA\Parameter(
 *          name="car_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Success delete inspection"),
 *     @OA\Response(response="500", description="Couldn't delete inspection."),
 * ),
 */
class InspectionController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Inspection",
     *             type="object",
     *         example={
     *             "inspection": {
     *                  "id": 2,
     *                  "car_id": 2,
     *                  "end_date": null,
     *                  "created_at": "2022-01-15T17:54:27.000000Z",
     *                  "updated_at": "2022-01-15T17:54:27.000000Z",
     *                  "car": {
     *                      "id": 2,
     *                      "owner_id": "3279240c-0da9-4d97-9423-8444835f1f4d",
     *                      "brand": "AAA123",
     *                      "description": "Lorem Ipsum",
     *                      "thumbnail": null,
     *                      "created_at": "2022-01-15T17:54:27.000000Z",
     *                      "updated_at": "2022-01-15T17:54:27.000000Z",
     *                      "family_id": null,
     *                      "availability": "available",
     *                      "details": null
     *                  }
     *             }
     *         },
     * )
     */
    /**
     * @throws InspectionNotRetrievedFromDatabaseException
     */
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
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateInspection",
     *             type="object",
     *         @OA\Property(
     *             property="end_date",
     *             type="date:d-m-Y|required"
     *         ),
     *         example={
     *              "end_date": "02-01-2022"
     *         },
     * )
     */
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
