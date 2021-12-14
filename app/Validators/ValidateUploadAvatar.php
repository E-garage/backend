<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateUploadAvatar
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'image' => 'image|mimes:jpeg,png|size:5024',
        ];

        $validator = Validator::make($request->only('image'), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
