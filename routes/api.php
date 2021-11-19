<?php

use App\Http\Controllers\User\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/email/verify/{id}/{hash}', fn(EmailVerificationRequest $request) => $request->fulfill())
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');

Route::prefix('/v1/auth')->group(function ()
{
    Route::post('/signup', [RegisterController::class, 'create'])
        ->middleware('validate.register')
        ->name('register');

    Route::get('/login', [LoginController::class, 'login'])
        ->middleware('validate.login')
        ->name('login');
});
