<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Exceptions\UserNotSavedToDatabaseException;
use App\Factories\UserFactory;
use App\Http\Controllers\Controller;
use App\Services\UserRegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private UserFactory $userFactory;

    public function __construct()
    {
        $this->userFactory = new UserFactory();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/signup",
     *     tags={"User"},
     *     summary="Operates about user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Register",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Register",
     *             ),
     *         ),
     *  ),
     * ),
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Register",
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
     */
    /**
     * @throws UserNotSavedToDatabaseException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        $user = $this->userFactory->createFromRequest($data);
        $service = new UserRegisterService($user);

        $service->register();

        return new JsonResponse($user, 201);
    }

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
