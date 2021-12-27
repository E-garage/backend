<?php

namespace App\Http\Controllers\User;

use App\Exceptions\TokenNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\UserLogoutService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    private UserLogoutService $userLogoutService;

    public function __construct()
    {
        $this->userLogoutService = new UserLogoutService();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/auth/logout",
     *     tags={"User"},
     *     summary="Operates about user",
     *     @OA\Parameter(
     *         parameter="user_accessToken",
     *         name="Bearer",
     *         in="header",
     *         required=true,
     *         description="accessToken to log out",
     *     ),
     *
     *     @OA\Response(
     *         response="201",
     *         description="Logged out",
     *         @OA\JsonContent(type="object",
     *                  @OA\Property(property="message", type="string"),
     *                  example={"message": "loged out"}
     *          ),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unauthorized",
     *     ),
     *    ),
     *
     * @throws TokenNotFoundException
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $this->userLogoutService->logout($user);
            $response = [
                'message' => 'Logged out',
            ];

            return new JsonResponse($response, 200);
        } catch (ModelNotFoundException $e) {
            throw new TokenNotFoundException();
        }
    }
}
