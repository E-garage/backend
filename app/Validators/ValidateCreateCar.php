<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateCreateCar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'brand' => 'string|min:3|max:30|required',
            'description' => 'string|max:50',
            'thumbnail' => 'image|mimes:jpeg,png',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
