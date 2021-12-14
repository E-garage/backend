<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Services\AvatarUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\POST(
 *     path="/api/v1/account/upload-avatar",
 *     tags={"Avatar"},
 *     summary="Upload user's avatar",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Avatar needed to perform action. Acceptable extensions: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/Avatar"),
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/Avatar"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description=""),
 * ),
 */
class AvatarController extends Controller
{
    protected UserModel $user;
    protected AvatarUploadService $service;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->service = new AvatarUploadService($this->user);
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="Avatar",
     *             type="object",
     *         @OA\Property(
     *             property="image",
     *             type="file"
     *         ),
     *         example={"image": "file"}
     * )
     */
    public function upload(Request $request): JsonResponse
    {
        $avatar = $request['image'];

        $this->service->deletePreviousAvatar();
        $pathToAvatar = $this->service->uploadAvatar($avatar);
        $this->service->saveAvatarNameInDB($pathToAvatar);

        return new JsonResponse(null, 200);
    }
}
