<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ValidateUpdateFamily
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
            'name' => 'string|min:5|max:30',
            'description' => 'string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
