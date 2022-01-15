<?php

namespace App\Http\Controllers\User;

use App\Exceptions\PasswordNotResetedException;
use App\Exceptions\ResetLinkNotSentException;
use App\Http\Controllers\Controller;
use App\Services\ResetPasswordService;
use App\Services\SendResetPasswordLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\PUT(
 *     path="/api/v1/reset-password/send-link",
 *     tags={"Reset Password"},
 *     summary="Send link to user's mail via mail.",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/SendResetPasswordLink"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Succes reset password"),
 *     @OA\Response(response="500", description="Reset Link Not Sent"),
 * ),
 *
 * @OA\PUT(
 *     path="/api/v1/reset-password/",
 *     tags={"Reset Password"},
 *     summary="Resets password.",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/ResetPassword"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Succes update password"),
 *     @OA\Response(response="500", description="Password Not Reseted"),
 * ),
 */
class ResetPasswordController extends Controller
{
    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="SendResetPasswordLink",
     *             type="object",
     *         @OA\Property(
     *             property="email",
     *             type="email|string"
     *         ),
     *         example={"email": "cool@email.com"}),
     * )
     */
    /**
     * @throws ResetLinkNotSentException
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        $email = $request->only('email');
        $service = new SendResetPasswordLinkService();
        $isSent = $service->sendLink($email);

        if (!$isSent) {
            throw new ResetLinkNotSentException();
        }

        return new JsonResponse();
    }

    /**
     * @OA\Component(
     *         @OA\Schema(
     *             schema="ResetPassword",
     *             type="object",
     *         @OA\Property(
     *             property="token",
     *             type="string",
     *         ),
     *         @OA\Property(
     *             property="email",
     *             type="email|string",
     *         ),
     *         @OA\Property(
     *             property="password",
     *             type="string",
     *         ),
     *         @OA\Property(
     *             property="password_confirmation",
     *             type="string",
     *         ),
     *         example={
     *             "token": "cdacc0d252881102003fb486b419f2de52d8f7b1779585342e5b04c2003ce10d",
     *             "email": "cool@email.com",
     *             "password": "12345678",
     *             "password_confirmation": "12345678",
     *         }),
     * )
     */
    /**
     * @throws PasswordNotResetedException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        $service = new ResetPasswordService();

        $isReseted = $service->reset($data);

        if (!$isReseted) {
            throw new PasswordNotResetedException();
        }

        return new JsonResponse();
    }

    private function getDataFromRequest(Request $request): array
    {
        $data = [
            'token' => $request['token'],
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        return $data;
    }
}
