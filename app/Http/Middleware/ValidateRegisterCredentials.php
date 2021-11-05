<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateRegisterCredentials
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8|max:50|confirmed',
            'password_confirmation' => 'min:8|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return new JsonResponse("The given data was invalid.", 400);
        }

        return $next($request);
    }
}
