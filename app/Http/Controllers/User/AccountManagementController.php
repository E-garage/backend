<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Services\UserAccountManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Put(
 *     path="/api/v1/account/update/password",
 *     tags={"Account Management"},
 *     summary="Update user's account password",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Data needed to perform action.",
 *         @OA\Schema(ref="#/components/schemas/UpdatePassword"),
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdatePassword"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description=""),
 * ),
 *
 * @OA\Put(
 *     path="/api/v1/account/update/name",
 *     tags={"Account Management"},
 *     summary="Update user's account name",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Data needed to perform action.",
 *         @OA\Schema(ref="#/components/schemas/UpdateName"),
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateName"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description=""),
 * ),
 *
 * @OA\Put(
 *     path="/api/v1/account/update/email",
 *     tags={"Account Management"},
 *     summary="Update user's account email",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Data needed to perform action.",
 *         @OA\Schema(ref="#/components/schemas/UpdateEmail"),
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateEmail"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description=""),
 * )
 */
class AccountManagementController extends Controller
{
    protected UserModel $user;
    protected UserAccountManagementService $service;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->service = new UserAccountManagementService($this->user);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdatePassword",
     *             type="object",
     *         @OA\Property(
     *             property="password",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="password_confirmation",
     *             type="string"
     *         ),
     *         example={"password": "12345678", "password_confirmation": "12345678"}
     * )
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $newPassword = (string)$request['password'];
        $this->service->updatePassword($newPassword);

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateEmail",
     *             type="object",
     *         @OA\Property(
     *             property="email",
     *             type="email|string"
     *         ),
     *         example={"email": "test@test.com"}
     * )
     */
    public function updateEmail(Request $request): JsonResponse
    {
        $newEmail = (string)$request['email'];
        $this->service->updateEmail($newEmail);

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="UpdateName",
     *             type="object",
     *         @OA\Property(
     *             property="name",
     *             type="string"
     *         ),
     *         example={"name": "JohnDoe"}
     * )
     */
    public function updateName(Request $request): JsonResponse
    {
        $newName = (string)$request['name'];
        $this->service->updateName($newName);

        return new JsonResponse();
    }
}
