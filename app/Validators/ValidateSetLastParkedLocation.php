<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ValidateSetLastParkedLocation
{
    /**
     * Handle an incoming request.
     *
     * @param Closure $next (\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'longitude' => ['numeric', 'required', 'regex:/^(\+|-)(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/'],
            'latitude' => ['numeric', 'required', 'regex:/^(\+|-)(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
