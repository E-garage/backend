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

/**
 * * @OA\GET(
 *     path="/api/v1/cars/insurance/{car_id}",
 *     tags={"Car's Insurance Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Get the expire date of car's insurance for the given car.",
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
 *                 ref="#/components/schemas/Insurance",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(response="404", description="Couldn't retrieve insurance."),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/cars/insurance/{car_id}/update",
 *     tags={"Car's Insurance Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Set the expire date of car's insurance for the given car.",
 *     @OA\Parameter(
 *     name="car_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateInsurance"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success update insurance"),
 *     @OA\Response(response="422", description="Couldn't update insurance."),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/cars/insurance/{car_id}/delete",
 *     tags={"Car's Insurance Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Delete the expire date of car's insurance for the given car.",
 *     @OA\Parameter(
 *          name="car_id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Success delete insurance"),
 *     @OA\Response(response="500", description="Couldn't delete insurance."),
 * ),
 */
class InsuranceController extends Controller
{
    /**
     * @OA\Component(
     *    @OA\Schema(
     *        schema="Insurance",
     *        type="object",
     *        example={
     *         "insurance": {
     *             "id": 2,
     *             "car_id": 2,
     *             "end_date": null,
     *             "created_at": "2022-01-15T17:54:27.000000Z",
     *             "updated_at": "2022-01-15T17:54:27.000000Z",
     *             "car": {
     *                 "id": 2,
     *                 "owner_id": "3279240c-0da9-4d97-9423-8444835f1f4d",
     *                 "brand": "AAA123",
     *                 "description": "Lorem Ipsum",
     *                 "thumbnail": null,
     *                 "created_at": "2022-01-15T17:54:27.000000Z",
     *                 "updated_at": "2022-01-15T17:54:27.000000Z",
     *                 "family_id": null,
     *                 "availability": "available",
     *                 "details": null
     *             }
     *         }
     *    },
     * )
     */
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
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateInsurance",
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
