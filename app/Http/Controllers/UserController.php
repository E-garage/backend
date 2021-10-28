<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\RegistrarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Create user.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $credentials = $this->getCredentialsFromRequest($request);
        $registrar = new RegistrarService($credentials);
        $user = $registrar->register();

        return new JsonResponse($user, 201);
    }

    /**
     * Extract credentials from request.
     * @param Request $request
     * @return Collection
     */
    private function getCredentialsFromRequest(Request $request): Collection
    {
        $credentials = new Collection([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $credentials;
    }
}
