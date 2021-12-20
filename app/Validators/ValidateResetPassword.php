<?php

namespace App\Validators;

use Closure;
use Illuminate\Http\Request;

class ValidateResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|max:50|confirmed',
            'password_confirmation' => 'min:8|max:50',
        ];

        return $next($request);
    }
}
