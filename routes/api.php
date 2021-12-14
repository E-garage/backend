<?php

use App\Http\Controllers\User\AccountManagementController;
use App\Http\Controllers\User\AvatarController;
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
});

Route::prefix('/v1/account')
->middleware('auth:sanctum')
->group(function ()
{
    Route::prefix('/update')->group(function ()
    {
        Route::put('/password', [AccountManagementController::class, 'updatePassword'])->middleware('validate.update.password');
        Route::put('/email', [AccountManagementController::class, 'updateEmail'])->middleware('validate.update.email');
        Route::put('/name', [AccountManagementController::class, 'updateName'])->middleware('validate.update.name');
    });

    Route::post('/upload-avatar', [AvatarController::class, 'upload'])->middleware('validate.upload.avatar');
});
