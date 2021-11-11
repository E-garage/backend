<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Factories\UserFactory;
use App\Http\Controllers\Controller;
use App\Services\UserRegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private UserFactory $userFactory;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userFactory = new UserFactory();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/signup",
     *     tags={"User"},
     *     summary="Operates about user",
     *     @OA\Parameter(
     *         parameter="user_credentials_in_query_required",
     *         name="body",
     *         in="query",
     *         required=true,
     *         description="User object that needs to be added to the database.",
     *         @OA\Schema(ref="#/components/schemas/User"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/User",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/User",
     *             ),
     *         ),
     *  ),
     * ),
     * @OA\Component(
     *         @OA\Schema(
     *             schema="User",
     *             type="object",
     *         @OA\Property(
     *             property="name",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="email",
     *             type="email|string"
     *         ),
     *         @OA\Property(
     *             property="password",
     *             type="string"
     *         ),
     *         @OA\Property(
     *             property="password_confirmation",
     *             type="string"
     *         ),
     *         example={"name": "JohnDoe", "email": "cool@email.com", "password": "12345678", "password_confirmation": "12345678"}
     *   )
     *
     * @throws \App\Exceptions\UserNotSavedToDatabaseException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        $user = $this->userFactory->createFromRequest($data);
        $register = new UserRegisterService($user);

        $register->register();

        return new JsonResponse($user, 201);
    }

    /**
     * Extract data from request.
     */
    private function getDataFromRequest(Request $request): array
    {
        $data = [
            'name' => (string)$request['name'],
            'email' => (string)$request['email'],
            'password' => (string)$request['password'],
        ];

        return $data;
    }
}
