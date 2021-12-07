<?php

namespace App\Http\Controllers\User;


use App\Exceptions\TokenNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
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
     * @param Request $request
     * @return JsonResponse
     * @throws TokenNotFoundException
     */
    public function logout(Request $request): JsonResponse
    {
        if ( Auth::check() || !is_null($request->bearerToken()) ) {
            try {
                auth()->user()->currentAccessToken()->delete();
                $response = [
                    'message' => 'Logged out',
                ];
                return new JsonResponse($response, 201);
            }catch (ModelNotFoundException $e){
                throw new TokenNotFoundException;
            }
        } else {
            $response = [
                'message' => 'Unauthorized',
            ];
            return new JsonResponse($response, 500);
        }
    }
}
