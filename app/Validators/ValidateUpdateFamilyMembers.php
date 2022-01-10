<?php

declare(strict_types = 1);

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ValidateUpdateFamilyMembers
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
            'names' => 'prohibits:emails|array|min:1|required_without:emails',
            'names.*' => 'string|distinct|min:3|max:50',
            'emails' => 'prohibits:names|array|min:1|required_without:names',
            'emails.*' => 'email|distinct',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
