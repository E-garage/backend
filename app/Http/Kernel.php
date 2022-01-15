<?php

declare(strict_types = 1);

namespace App\Http;

use App\Validators\ValidateCreateCar;
use App\Validators\ValidateCreateFamily;
use App\Validators\ValidateCreateRefueling;
use App\Validators\ValidateLoginCredentials;
use App\Validators\ValidateRegisterCredentials;
use App\Validators\ValidateResetPassword;
use App\Validators\ValidateSendResetLink;
use App\Validators\ValidateSetLastParkedLocation;
use App\Validators\ValidateUpdateCar;
use App\Validators\ValidateUpdateCarDetails;
use App\Validators\ValidateUpdateEmail;
use App\Validators\ValidateUpdateFamily;
use App\Validators\ValidateUpdateFamilyCars;
use App\Validators\ValidateUpdateFamilyMembers;
use App\Validators\ValidateUpdateLastPayment;
use App\Validators\ValidateUpdateName;
use App\Validators\ValidateUpdateOriginalBudget;
use App\Validators\ValidateUpdatePassword;
use App\Validators\ValidateUpdateRefueling;
use App\Validators\ValidateUploadAvatar;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'validate.register' => ValidateRegisterCredentials::class,
        'validate.login' => ValidateLoginCredentials::class,
        'validate.update.password' => ValidateUpdatePassword::class,
        'validate.update.email' => ValidateUpdateEmail::class,
        'validate.update.name' => ValidateUpdateName::class,
        'validate.upload.avatar' => ValidateUploadAvatar::class,
        'validate.send.reset.link' => ValidateSendResetLink::class,
        'validate.reset.password' => ValidateResetPassword::class,
        'validate.create.car' => ValidateCreateCar::class,
        'validate.update.car' => ValidateUpdateCar::class,
        'validate.set.location' => ValidateSetLastParkedLocation::class,
        'validate.create.family' => ValidateCreateFamily::class,
        'validate.update.family' => ValidateUpdateFamily::class,
        'validate.update.family.members' => ValidateUpdateFamilyMembers::class,
        'validate.update.family.cars' => ValidateUpdateFamilyCars::class,
        'validate.update.car.details' => ValidateUpdateCarDetails::class,
        'validate.create.refueling' => ValidateCreateRefueling::class,
        'validate.update.refueling' => ValidateUpdateRefueling::class,
        'validate.update.original.budget' => ValidateUpdateOriginalBudget::class,
        'validate.update.last.payment' => ValidateUpdateLastPayment::class,
    ];
}
