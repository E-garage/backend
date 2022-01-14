<?php

namespace App\Http\Controllers;

use App\Exceptions\CarNotFoundException;
use App\Factories\RefuelingFactory;
use App\Models\Refueling;
use App\Services\AddRefuelingService;
use App\Services\AttachReceiptToRefuelingService;
use App\Services\DeleteRefuelingService;
use App\Services\IndexCarsService;
use App\Services\IndexRefuelingService;
use App\Services\UpdateRefuelingService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\POST(
 *     path="/api/v1/refueling/add",
 *     tags={"Refueling Management"},
 *     summary="Add refueling.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Acceptable extensions for receipt: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/Refueling"),
 *     ),
 *     @OA\Response(response="201", description="Success"),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/refueling",
 *     tags={"Refueling Management"},
 *     summary="Get all refueling that logged user own.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/RefuelingCollection",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/refueling/update/{refueling_id}",
 *     tags={"Refueling Management"},
 *     summary="Update refueling's info or receipt.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Acceptable extensions for receipt: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/RefuelingUpdate"),
 *     ),
 *     @OA\Response(response="200", description="Success"),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/refueling/delete/{refueling_id}",
 *     tags={"Refueling Management"},
 *     summary="Delete refueling.",
 *     @OA\Response(response="200", description="Success"),
 * ),
 */
class RefuelingController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Refueling",
     *             type="object",
     *         @OA\Property(
     *             property="date",
     *             type="string|required"
     *         ),
     *     @OA\Property(
     *             property="FuelType",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="amount",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="TotalPrice",
     *             type="string|required"
     *         ),
     *     @OA\Property(
     *             property="receipt",
     *             type="file"
     *         ),
     *         example={
     *              "date": "15/10/2020",
     *              "FuelType": "gasoline",
     *              "amount": "50l",
     *              "TotalPrice": "200",
     *              "receipt": "file"
     *         },
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->getDataFormRequest($request);

        $service = new IndexCarsService();
        if (!$service->findByID($data['car_id'])) {
            throw new CarNotFoundException();
        }

        $receipt = $request['receipt'];
        $data['date'] = \Date::createFromFormat('m/d/Y', $data['date'])->format('Y-m-d');

        $factory = new RefuelingFactory();
        $refueling = $factory->createFromRequest($data);

        if ($receipt) {
            $service = new AttachReceiptToRefuelingService($refueling, $receipt);
            $refueling = $service->attachReceipt();
        }

        $service = new AddRefuelingService($refueling);
        $service->addRefueling();

        return new JsonResponse(null, 201);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="RefuelingCollection",
     *             type="object",
     *         @OA\Property(
     *             property="refueling",
     *             type="list",
     *         ),
     *         example={
     *              {
     *                  "date": "15/10/2020",
     *                  "FuelType": "gasoline",
     *                  "amount": "50l",
     *                  "TotalPrice": "200",
     *                  "receipt": "file"
     *              }
     *         },
     * )
     */
    public function get(): JsonResponse
    {
        $service = new IndexRefuelingService();
        $refueling = $service->index();

        return new JsonResponse(['refueling' => $refueling]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="RefuelingUpdate",
     *             type="object",
     *         @OA\Property(
     *             property="date",
     *             type="string"
     *         ),
     *
     *        @OA\Property(
     *             property="FuelType",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="amount",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="TotalPrice",
     *             type="string|required"
     *         ),
     *     @OA\Property(
     *             property="receipt",
     *             type="file"
     *         ),
     *         example={
     *              "date": "15/10/2020",
     *              "FuelType": "gasoline",
     *              "amount": "50l",
     *              "TotalPrice": "200",
     *              "receipt": "file"
     *         },
     * )
     */
    public function update(Refueling $refueling, Request $request): JsonResponse
    {
        if (Auth::user()->cannot('update', $refueling)) {
            return new JsonResponse(null, 401);
        }
        $receipt = $request['receipt'];

        if ($receipt) {
            $service = new AttachReceiptToRefuelingService($refueling, $receipt);
            $refueling = $service->attachReceipt();
        }

        $data = $request->only(['date', 'FuelType', 'amount']);
        $service = new UpdateRefuelingService($refueling, $data);
        $service->update();

        return new JsonResponse();
    }

    public function delete(Refueling $refueling): JsonResponse
    {
        if (Auth::user()->cannot('delete', $refueling)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteRefuelingService($refueling);
        $service->deleteRefueling();

        return new JsonResponse();
    }

    private function getDataFormRequest(Request $request): array
    {
        $data = [
            'owner_id' => Auth::user()->id, //@phpstan-ignore-line
            'car_id' => $request['car_id'],
            'amount' => $request['amount'],
            'FuelType' => $request['FuelType'],
            'TotalPrice' => $request['TotalPrice'],
            'date' => $request['date'],
        ];

        return $data;
    }
}
