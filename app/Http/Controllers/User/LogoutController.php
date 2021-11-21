<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request): JsonResponse
    {
        if ( $request->bearerToken()) {
            auth()->user()->tokens()->delete();
            $response = [
                'message' => 'Logged out',
            ];
            return new JsonResponse($response, 201);
        } else {
            $response = [
                'message' => 'unauthorized',
            ];
            return new JsonResponse($response, 500);
        }
    }

}
