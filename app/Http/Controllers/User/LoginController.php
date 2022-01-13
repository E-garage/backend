<?php

namespace App\Http\Controllers\User;

use App\Factories\UserFactory;
use App\Http\Controllers\Controller;
use App\Services\UserLoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private UserFactory $userFactory;

    public function __construct()
    {
        $this->userFactory = new UserFactory();
    }

    /**
     * @OA\POST(
     *     path="/api/v1/auth/login",
     *     tags={"User"},
     *     summary="Operates about user",
     *     @OA\Parameter(
     *         parameter="user_credentials_in_query_required",
     *         name="body",
     *         in="query",
     *         required=true,
     *         description="User object that needs to be log in.",
     *         @OA\Schema(ref="#/components/schemas/Login"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Login",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Loged in",
     *         @OA\JsonContent(type="object",
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="accessToken", type="string"),
     *                  example={"email": "cool@email.com", "accessToken": "tokenNr1", "tokenType": "Bearer"}
     *          ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User Not Found",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="User Credential Invalid",
     *  ),
     * ),
     *     @OA\Component(
     *         @OA\Schema(
     *             schema="Login",
     *             type="object",
     *         @OA\Property(
     *             property="email",
     *             type="email|string"
     *         ),
     *         @OA\Property(
     *             property="password",
     *             type="string"
     *         ),
     *         example={"email": "cool@email.com", "password": "12345678"}
     *   )
     *
     * @throws \App\Exceptions\UserNotFoundException
     */
    public function login(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        $user = $this->userFactory->getUser($data);
        $userLoginService = new UserLoginService($user);

        $loggedUser = $userLoginService->login();

        $token = $loggedUser->createToken('auth_token')->plainTextToken;
        $response = [
            'user' => $user,
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ];

        return new JsonResponse($response, 200);
    }

    private function getDataFromRequest(Request $request): array
    {
        $data = [
            'email' => (string)$request['email'],
            'password' => (string)$request['password'],
        ];

        return $data;
    }
}
