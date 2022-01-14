<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Exceptions\UserNotUpdatedException;
use App\Http\Controllers\Controller;
use App\Services\UserAccountManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Put(
 *     path="/api/v1/account/update/password",
 *     tags={"Account Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Update user's account password",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdatePassword"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success"),
 * ),
 *
 * @OA\Put(
 *     path="/api/v1/account/update/name",
 *     tags={"Account Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Update user's account name",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UpdateName"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Success"),
 * ),
 *
 * @OA\Put(
 *     path="/api/v1/account/update/email",
 *     tags={"Account Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Update user's account email",
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
    protected UserAccountManagementService $service;

    public function __construct()
    {
        $this->service = new UserAccountManagementService();
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
    /**
     * @throws UserNotUpdatedException
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
    /**
     * @throws UserNotUpdatedException
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
    /**
     * @throws UserNotUpdatedException
     */
    public function updateName(Request $request): JsonResponse
    {
        $newName = (string)$request['name'];
        $this->service->updateName($newName);

        return new JsonResponse();
    }
}
