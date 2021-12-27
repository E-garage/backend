<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Services\AvatarManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\POST(
 *     path="/api/v1/account/avatar/upload",
 *     tags={"Avatar Management"},
 *     summary="Upload user's avatar",
 *     @OA\Parameter(
 *         parameter="user_credentials_in_query_required",
 *         name="body",
 *         in="query",
 *         required=true,
 *         description="Avatar needed to perform action. Acceptable extensions: png, jpg, jpeg.",
 *         @OA\Schema(ref="#/components/schemas/Avatar"),
 *     ),
 *     @OA\Response(response="201", description="Success"),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/account/avatar",
 *     tags={"Avatar Management"},
 *     summary="Get user's avatar",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="image",
 *             @OA\Schema(
 *                 ref="#/components/schemas/Avatar",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/account/avatar/delete",
 *     tags={"Avatar Management"},
 *     summary="Delete user's avatar",
 *     @OA\Response(response="200", description="Success"),
 * ),
 */
class AvatarController extends Controller
{
    protected UserModel $user;
    protected AvatarManagementService $service;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->service = new AvatarManagementService($this->user);
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
        $this->service->deleteAvatar();
        $this->service->uploadAvatar($avatar);

        return new JsonResponse();
    }

    public function get(): StreamedResponse
    {
        $avatar = $this->service->getAvatar();

        return $avatar;
    }

    public function delete(): JsonResponse
    {
        $this->service->deleteAvatar();

        return new JsonResponse();
    }
}
