<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ValidateUpdateCarDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $rules = [
            'engine_capacity' => 'string|min:3|max:5',
            'horse_power' => 'string|max:4',
            'sits' => 'string|max:30',
            'doors' => 'string|max:30',
            'color' => 'string|max:30',
            'drivetrain' => 'string|max:30',
            'body' => 'string|max:30',
            'Fuel_Type' => 'string|max:30',
            'mileage' => 'string|max:30',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
