<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Exceptions\LastParkedLocationNotRetrievedException;
use App\Exceptions\LastParkedLocationNotUpdatedException;
use App\Services\LastParkedLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\POST(
 *     path="/api/v1/last-parked-location/set",
 *     tags={"Last parked location management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Set coordinates.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Coordinates of last parked location.",
 *         @OA\Schema(ref="#/components/schemas/Coordinates"),
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     @OA\Response(response="500", description="Couldn't update the location."),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/last-parked-location/",
 *     tags={"Last parked location management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Get coordinates.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/Coordinates"),
 *         ),
 *     ),
 *     @OA\Response(response="500", description="Couldn't retrieve location."),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/last-parked-location/delete",
 *     tags={"Last parked location management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Delete coordinates.",
 *     @OA\Response(response="200", description="Success delete"),
 *     @OA\Response(response="500", description="Couldn't update the location."),
 * ),
 */
class LastParkedLocationController extends Controller
{
    protected LastParkedLocationService $service;

    public function __construct()
    {
        $this->service = new LastParkedLocationService();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Coordinates",
     *             type="object",
     *         @OA\Property(
     *             property="longitude",
     *             type="numeric|required"
     *         ),
     *         @OA\Property(
     *             property="latitude",
     *             type="numeric|required"
     *         ),
     *         example={
     *              "longitude": "+50.235423",
     *              "latitude": "-15.152354",
     *         },
     * )
     */
    /**
     * @throws LastParkedLocationNotUpdatedException
     */
    public function set(Request $request): JsonResponse
    {
        $data = $request->only(['longitude', 'latitude']);
        $this->service->setLocation($data);

        return new JsonResponse();
    }

    /**
     * @throws LastParkedLocationNotRetrievedException
     */
    public function get(): JsonResponse
    {
        $coordinates = $this->service->getLocation();

        return new JsonResponse($coordinates);
    }

    /**
     * @throws LastParkedLocationNotUpdatedException
     */
    public function delete(): JsonResponse
    {
        $this->service->deleteLocation();

        return new JsonResponse();
    }
}
