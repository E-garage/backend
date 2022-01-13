<?php

namespace App\Http\Controllers;

use App\Factories\FamilyFactory;
use App\Models\Car;
use App\Models\Family;
use App\Services\CreateFamilyService;
use App\Services\DeleteFamilyService;
use App\Services\IndexFamiliesService;
use App\Services\ShowFamilyService;
use App\Services\UpdateFamilyService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\POST(
 *     path="/api/v1/family-sharing/create",
 *     tags={"Family Sharing Management"},
 *     summary="Add family.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Name and/or description of family.",
 *         @OA\Schema(ref="#/components/schemas/FamilyCreate"),
 *     ),
 *     @OA\Response(response="201", description="Success"),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/family-sharing",
 *     tags={"Family Sharing Management"},
 *     summary="Get all families that current user is part of (member or creator).",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/FamilyCollection",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/family-sharing/{family_id}",
 *     tags={"Family Sharing Management"},
 *     summary="Get chosen family with details. Restricted for members and owners of chosen family.",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/FamilyWithDetails",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/family-sharing/update/{family_id}",
 *     tags={"Family Sharing Management"},
 *     summary="Update family's name or description. Restricted for owners.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Name or/and description of family to put.",
 *         @OA\Schema(ref="#/components/schemas/FamilyUpdateDetails"),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Family",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/family-sharing/update/{family_id}/members",
 *     tags={"Family Sharing Management"},
 *     summary="Add or remove members. Restricted for owners.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Array of users' names or array of users' emails. Request will be rejected if both exists in it.",
 *         @OA\Schema(ref="#/components/schemas/FamilyUpdateMembers"),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Family",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/family-sharing/update/{family_id}/cars",
 *     tags={"Family Sharing Management"},
 *     summary="Add cars to family. Restricted for owners.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Array of cars' ids.",
 *         @OA\Schema(ref="#/components/schemas/FamilyUpdateCars"),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Family",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/family-sharing/update/{family_id}/{car_id}/detach",
 *     tags={"Family Sharing Management"},
 *     summary="Remove car from family. Restricted for owners.",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true
 *     ),
 *     @OA\Response(
 *           response="200",
 *           description="Success",
 *           @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  ref="#/components/schemas/Family",
 *              ),
 *          ),
 *     ),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/family-sharing/delete/{family_id}",
 *     tags={"Family Sharing Management"},
 *     summary="Delete family. Restricted for owners.",
 *     @OA\Response(response="200", description="Success"),
 * ),
 */
class FamilyController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyCollection",
     *             type="object",
     *         @OA\Property(
     *             property="families",
     *             type="list",
     *         ),
     *         example={
     *              {
     *                  "id": 62,
     *                  "owner_id": "dd7dd0cc-4444-48ee-5555-2de4df555555",
     *                  "name": "HolyCoffee Company",
     *                  "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *                  "created_at": "2022-01-12T08:42:38.000000Z",
     *                  "updated_at": "2022-01-12T08:42:38.000000Z"
     *              },
     *              {
     *                  "id": 62,
     *                  "owner_id": "dd7dd0cc-4444-48ee-5555-2de4df555555",
     *                  "name": "The Smiths",
     *                  "description": null,
     *                  "created_at": "2022-01-12T08:42:38.000000Z",
     *                  "updated_at": "2022-01-12T08:42:38.000000Z"
     *              }
     *         },
     * )
     */
    public function get(): JsonResponse
    {
        $service = new IndexFamiliesService();
        $families = $service->index();

        return new JsonResponse(['families' => $families->flatten()]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyCreate",
     *             type="object",
     *         @OA\Property(
     *             property="name",
     *             type="string|min:5|max:30|required"
     *         ),
     *         @OA\Property(
     *             property="description",
     *             type="string|max:50"
     *         ),
     *         example={
     *              "name": "Family's Car",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi."
     *         },
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'description']);

        $factory = new FamilyFactory();
        $family = $factory->createFormRequest($data);

        $service = new CreateFamilyService($family);
        $service->create();

        return new JsonResponse(null, 201);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyWithDetails",
     *             type="object",
     *             example={
     *              "family": {
     *                  "id": 64,
     *                   "owner_id": "dd7f80c9-4ab6-48ec-be00-2de4df5b9157",
     *                   "name": "testt family",
     *                   "description": "lorem ipsum",
     *                    "created_at": "2022-01-12T08:42:29.000000Z",
     *                    "updated_at": "2022-01-12T08:42:29.000000Z",
     *                    "members":
     *                        {
     *                            "id": "0e6375d5-703a-476a-8ad5-fbe713de8e6a",
     *                            "name": "Greg",
     *                            "email": "cooles22222temail@ivseen.com",
     *                            "email_verified_at": null,
     *                            "created_at": "2022-01-12T09:08:10.000000Z",
     *                            "updated_at": "2022-01-12T09:08:10.000000Z",
     *                            "avatar": null,
     *                            "role": "user",
     *                            "pivot": {
     *                                "family_id": 64,
     *                                "user_id": "0e6375d5-703a-476a-8ad5-fbe713de8e6a"
     *                            }
     *                        },
     *                    "cars":
     *                        {
     *                            "id": 27,
     *                            "owner_id": "dd7f80c9-4ab6-48ec-be00-2de4df5b9157",
     *                            "brand": "AAA123",
     *                            "description": "Lorem Ipsum",
     *                            "thumbnail": null,
     *                            "created_at": "2022-01-12T09:07:26.000000Z",
     *                            "updated_at": "2022-01-12T09:07:58.000000Z",
     *                            "family_id": 64
     *                        }
     *
     *                }
     *          },
     * )
     */
    public function show(Family $family): JsonResponse
    {
        if (Auth::user()->cannot('view', $family)) {
            return new JsonResponse(null, 401);
        }

        $service = new ShowFamilyService($family);
        $family = $service->show();

        return new JsonResponse(['family' => $family]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyUpdateDetails",
     *             type="object",
     *         @OA\Property(
     *             property="name",
     *             type="string|min:5|max:30"
     *         ),
     *         @OA\Property(
     *             property="description",
     *             type="string|max:50"
     *         ),
     *         example={
     *              "name": "Family's Car",
     *              "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi."
     *         },
     * )
     *
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Family",
     *             type="object",
     *         example={
     *             "id": 62,
     *             "owner_id": "dd7dd0cc-4444-48ee-5555-2de4df555555",
     *             "name": "HolyCoffee Company",
     *             "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam tempora aperiam sint sequi.",
     *             "created_at": "2022-01-12T08:42:38.000000Z",
     *             "updated_at": "2022-01-12T08:42:38.000000Z"
     *         },
     * )
     */
    public function updateDetails(Request $request, Family $family): JsonResponse
    {
        if (Auth::user()->cannot('update', $family)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->only(['name', 'description']);

        $service = new UpdateFamilyService($family);
        $family = $service->updateDetails($data);

        return new JsonResponse(['family' => $family]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyUpdateMembers",
     *             type="object",
     *         @OA\Property(
     *             property="names",
     *             type="prohibits:emails|array|min:1|required_without:emails"
     *         ),
     *         @OA\Property(
     *             property="name",
     *             type="string|distinct|min:3|max:50"
     *         ),
     *         @OA\Property(
     *             property="emails",
     *             type="prohibits:names|array|min:1|required_without:names"
     *         ),
     *         @OA\Property(
     *             property="email",
     *             type="email|distinct"
     *         ),
     *         example={
     *              "emails": {
     *                  "email@email.com",
     *                  "another@email.com",
     *              },
     *
     *              "names": {
     *                  "Greg",
     *                  "Bob",
     *              }
     *         },
     * )
     */
    public function updateMembers(Request $request, Family $family): JsonResponse
    {
        if (Auth::user()->cannot('update', $family)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->get('names') ?? $request->get('emails');

        $service = new UpdateFamilyService($family);
        $family = $service->updateMembers($data);

        return new JsonResponse(['family' => $family]);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="FamilyUpdateCars",
     *             type="object",
     *         @OA\Property(
     *             property="cars",
     *             type="list"
     *         ),
     *         @OA\Property(
     *             property="car_id",
     *             type="int"
     *         ),
     *         example={
     *              "cars": {
     *                  2,
     *                  3,
     *                  26
     *              }
     *         },
     * )
     */
    public function updateCars(Request $request, Family $family): JsonResponse
    {
        if (Auth::user()->cannot('update', $family)) {
            return new JsonResponse(null, 401);
        }

        $data = $request->get('cars');

        $service = new UpdateFamilyService($family);
        $service->updateCars($data);

        return new JsonResponse();
    }

    public function detachCar(Family $family, Car $car): JsonResponse
    {
        if (Auth::user()->cannot('update', $family)) {
            return new JsonResponse(null, 401);
        }

        $service = new UpdateFamilyService($family);
        $service->detachCar($car);

        return new JsonResponse();
    }

    public function delete(Family $family): JsonResponse
    {
        if (Auth::user()->cannot('delete', $family)) {
            return new JsonResponse(null, 401);
        }

        $service = new DeleteFamilyService($family);
        $service->delete();

        return new JsonResponse();
    }
}
