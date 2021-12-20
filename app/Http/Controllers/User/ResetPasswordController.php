<?php

namespace App\Http\Controllers\User;

use App\Exceptions\PasswordNotResetedException;
use App\Exceptions\ResetLinkNotSentException;
use App\Services\SendResetPasswordLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ResetPasswordService;

class ResetPasswordController extends Controller
{
    public function sendResetLink(Request $request): JsonResponse
    {
        $email = $request->only('email');
        $service = new SendResetPasswordLinkService();
        $isSent = $service->sendLink($email);

        if(!$isSent) {
            throw new ResetLinkNotSentException();
        }

        return new JsonResponse(['link_sent' => $isSent]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        $service = new ResetPasswordService($data);

        $isReseted = $service->reset($data);

        if(!$isReseted) {
            throw new PasswordNotResetedException();
        }

        return new JsonResponse(['password_reseted' => $isReseted]);
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
