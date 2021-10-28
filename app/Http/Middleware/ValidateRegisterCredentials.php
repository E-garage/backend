<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateRegisterCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Mixed
    {
        $rules = [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8|max:50|confirmed',
            'password_confirmation' => 'min:8|max:50'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
            throw new ValidationException($validator);

        return $next($request);
    }
}
