<?php

namespace App\Http\Controllers\User;

use App\Exceptions\AvatarDeleteException;
use App\Exceptions\UserNotUpdatedException;
use App\Http\Controllers\Controller;
use App\Services\AvatarManagementService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\POST(
 *     path="/api/v1/account/avatar/upload",
 *     tags={"Avatar Management"},
 *     security={{"bearerAuth": {}}},
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
 *     @OA\Response(response="500", description="Couldnt delete avatar."),
 *     @OA\Response(response="422", description="Couldnt find or update the user."),
 * ),
 *
 * @OA\GET(
 *     path="/api/v1/account/avatar",
 *     tags={"Avatar Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Get user's avatar",
 *     @OA\Response(
 *          response="200",
 *          description="Success",
 *          @OA\MediaType(
 *             mediaType="image",
 *             @OA\Schema(
 *                 ref="#/components/schemas/RetrievedAvatar",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(response="404", description="Couldnt get avatar."),
 * ),
 *
 * @OA\DELETE(
 *     path="/api/v1/account/avatar/delete",
 *     tags={"Avatar Management"},
 *     security={{"bearerAuth": {}}},
 *     summary="Delete user's avatar",
 *     @OA\Response(response="200", description="Success"),
 *     @OA\Response(response="500", description="Couldnt delete avatar."),
 *     @OA\Response(response="422", description="Couldnt find or update the user.")
 * ),
 */
class AvatarController extends Controller
{
    protected AvatarManagementService $service;

    public function __construct()
    {
        $this->service = new AvatarManagementService();
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
    /**
     * @throws AvatarDeleteException
     * @throws UserNotUpdatedException
     */
    public function upload(Request $request): JsonResponse
    {
        $avatar = $request['image'];
        $this->service->deleteAvatar();
        $this->service->uploadAvatar($avatar);

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="RetrievedAvatar",
     *             type="object",
     *         @OA\Property(
     *             property="image",
     *             type="string",
     *             description="Image is encoded in base64."
     *         ),
     *         example={"image": "iVBORw0KGgoAAAANSUhEUgAAAFEAAABRCAYAAACqj0o2AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAzElEQVR4nO3QgQkAIRDAsNP9d/4fwoIgyQSla2a+4ci+HfACEwMmBkwMmBgwMWBiwMSAiQETAyYGTAyYGDAxYGLAxICJARMDJgZMDJgYMDFgYsDEgIkBEwMmBkwMmBgwMWBiwMSAiQETAyYGTAyYGDAxYGLAxICJARMDJgZMDJgYMDFgYsDEgIkBEwMmBkwMmBgwMWBiwMSAiQETAyYGTAyYGDAxYGLAxICJARMDJgZMDJgYMDFgYsDEgIkBEwMmBkwMmBgwMWBiwMTAD2JSAaEhoH6NAAAAAElFTkSuQmCC"}
     * )
     */
    /**
     * @throws FileNotFoundException
     */
    public function get(): JsonResponse
    {
        $avatar = $this->service->getAvatar();

        return new JsonResponse(['image' => $avatar]);
    }

    /**
     * @throws AvatarDeleteException
     * @throws UserNotUpdatedException
     */
    public function delete(): JsonResponse
    {
        $this->service->deleteAvatar();

        return new JsonResponse();
    }
}
