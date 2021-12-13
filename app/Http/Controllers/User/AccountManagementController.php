<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Services\UserAccountManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountManagementController extends Controller
{
    protected UserModel $user;
    protected UserAccountManagementService $service;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->service = new UserAccountManagementService($this->user);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $newPassword = (string)$request['password'];
        $this->service->updatePassword($newPassword);

        return new JsonResponse();
    }

    public function updateEmail(Request $request): JsonResponse
    {
        $newEmail = (string)$request['email'];
        $this->service->updateEmail($newEmail);

        return new JsonResponse();
    }

    public function updateName(Request $request): JsonResponse
    {
        $newName = (string)$request['name'];
        $this->service->updateName($newName);

        return new JsonResponse();
    }
}
