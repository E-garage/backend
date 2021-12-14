<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Services\AvatarUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    protected UserModel $user;
    protected AvatarUploadService $service;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->service = new AvatarUploadService($this->user);
    }

    public function upload(Request $request): JsonResponse
    {
        $avatar = $request['image'];

        $this->service->deletePreviousAvatar();
        $pathToAvatar = $this->service->uploadAvatar($avatar);
        $this->service->saveAvatarNameInDB($pathToAvatar);

        return new JsonResponse(null, 201);
    }
}
