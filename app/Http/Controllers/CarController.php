<?php

namespace App\Http\Controllers;

use App\Factories\CarFactory;
use App\Models\Car;
use App\Services\AddCarService;
use App\Services\AttachThumbnailToCarService;
use App\Services\DeleteCarService;
use App\Services\IndexCarsService;
use App\Services\UpdateCarService;
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
 *         @OA\Schema(ref="#/components/schemas/Car"),
 *     ),
 *     @OA\Response(response="201", description="Success"),
 * ),
 *
 * @OA\POST(
 *     path="/api/v1/cars/status/{car_id}",
 *     tags={"Car Management"},
 *     summary="Change availability status of car's.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/CarStatusUpdate",
 *             ),
 *         ),
 *     ),
 * ),
 * @OA\GET(
 *     path="/api/v1/cars",
 *     tags={"Car Management"},
 *     summary="Get all cars that logged user own.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/CarCollection",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/cars/update/{car_id}",
 *     tags={"Car Management"},
 *     summary="Update car's info or thumbnail.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Acceptable extensions for thumbnail: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/CarUpdate"),
 *     ),
 *     @OA\Response(response="200", description="Success"),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/cars/update/details/{car_id}",
 *     tags={"Car Management"},
 *     summary="Update car's details.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         @OA\Schema(ref="#/components/schemas/CarDetailsUpdate"),
 *     ),
 *     @OA\Response(response="200", description="Success"),
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
     *             schema="Car",
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
     *             property="thumbnail",
     *             type="file"
     *         ),
     *         example={
     *              "brand": "BMW X12",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *              "thumbnail": "file"
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

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="CarCollection",
     *             type="object",
     *         @OA\Property(
     *             property="cars",
     *             type="list",
     *         ),
     *         example={
     *              {
     *                  "brand": "BMW X12",
     *                  "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *                  "thumbnail": "file"
     *              },
     *              {
     *                  "brand": "BMW X12",
     *                  "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *                  "thumbnail": "file"
     *              }
     *         },
     * )
     */
    public function index(): JsonResponse
    {
        $service = new IndexCarsService();
        $cars = $service->index();

        return new JsonResponse(['cars' => $cars]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="CarUpdate",
     *             type="object",
     *         @OA\Property(
     *             property="brand",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="description",
     *             type="string"
     *         ),
     *        @OA\Property(
     *             property="thumbnail",
     *             type="file"
     *         ),
     *         example={
     *              "brand": "BMW X12",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *              "thumbnail": "file"
     *         },
     * )
     */
    public function update(Car $car, Request $request): JsonResponse
    {
        if (Auth::user()->cannot('update', $car)) {
            return new JsonResponse(null, 401);
        }

        $thumbnail = $request['thumbnail'];

        if ($thumbnail) {
            $service = new AttachThumbnailToCarService($car, $thumbnail);
            $car = $service->attachThumbnail();
        }

        $data = $request->only(['brand', 'description']);
        $service = new UpdateCarService($car, $data);
        $service->update();

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="CarDetailsUpdate",
     *             type="object",
     *         @OA\Property(
     *             property="engine_capacity",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="horsePower",
     *             type="string"
     *         ),
     *        @OA\Property(
     *             property="sits",
     *             type="string"
     *         ),
     *       @OA\Property(
     *             property="doors",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="color",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="drivetrain",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="body",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="Fuel_Type",
     *             type="string"
     *         ),
     *     @OA\Property(
     *             property="mileage",
     *             type="string"
     *         ),
     *         example={
     *              "engine_capacity":"2.0l",
     *              "horse_power":"200hp",
     *              "sits":"5",
     *              "doors":"5",
     *              "color":"silver",
     *              "drivetrain":"FWD",
     *              "body":"suv",
     *              "Feul_Type":"petrol",
     *              "mileage":"150000km"
     *         },
     * )
     */
    public function updateDetails(Car $car, Request $request): JsonResponse
    {
        if (Auth::user()->cannot('update', $car)) {
            return new JsonResponse(null, 401);
        }
        if (count($request->all()) > 0) {
            $data['details'] = $request->all();
        } else {
            $data['details'] = null;
        }

        $service = new UpdateCarService($car, $data);
        $service->update();

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="CarStatusUpdate",
     *             type="object",
     *         @OA\Property(
     *             property="brand",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="description",
     *             type="string"
     *         ),
     *        @OA\Property(
     *             property="thumbnail",
     *             type="file"
     *         ),
     *       @OA\Property(
     *             property="availability",
     *             type="string"
     *         ),
     *         example={
     *              "brand": "BMW X12",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *              "thumbnail": "file",
     *              "availability": "available"
     *         },
     * )
     */
    public function status(Car $car): JsonResponse
    {
        if (Auth::user()->cannot('update', $car)) {
            return new JsonResponse(null, 401);
        }
        $car->changeStatus();

        $service = new UpdateCarService($car, ['availability']);
        $service->update();

        return new JsonResponse();
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
