<?php

namespace App\Http\Controllers;

use App\Factories\CarFactory;
use App\Models\Car;
use App\Services\AddCarService;
use App\Services\AttachThumbnailToCarService;
use App\Services\DeleteCarService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\POST(
 *     path="/api/v1/cars/add",
 *     tags={"Car Management"},
 *     summary="Add car.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Acceptable extensions for thumbnail: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/AddCar"),
 *     ),
 *     @OA\Response(response="201", description="Success"),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/cars/delete/{car_id}",
 *     tags={"Car Management"},
 *     summary="Delete car.",
 *     @OA\Response(response="200", description="Success"),
 * ),
 */
class CarController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="AddCar",
     *             type="object",
     *         @OA\Property(
     *             property="brand",
     *             type="string|required"
     *         ),
     *         @OA\Property(
     *             property="description",
     *             type="string"
     *         ),
     *        @OA\Property(
     *             property="image",
     *             type="file"
     *         ),
     *         example={
     *              "brand": "BMW X12",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *              "image": "file"
     *         },
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->getDataFormRequest($request);
        $thumbnail = $request['thumbnail'];

        $factory = new CarFactory();
        $car = $factory->createFromRequest($data);

        if ($thumbnail) {
            $service = new AttachThumbnailToCarService($car, $thumbnail);
            $car = $service->attachThumbnail();
        }

        $service = new AddCarService($car);
        $service->addCar();

        return new JsonResponse(null, 201);
    }

    public function index()
    {
        // code...
    }

    public function show(Car $car)
    {
        // retrieve car with details
    }

    public function update(Car $car)
    {
        // code...
    }

    public function delete(Car $car): JsonResponse
    {
        if (Auth::user()->cannot('delete', $car)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteCarService($car);
        $service->deleteCar();

        return new JsonResponse();
    }

    private function getDataFormRequest(Request $request): array
    {
        $data = [
            'owner_id' => Auth::user()->id, //@phpstan-ignore-line
            'brand' => $request['brand'],
            'description' => $request['description'],
        ];

        return $data;
    }
}
