<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateLoginCredentials
{
    /**
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
