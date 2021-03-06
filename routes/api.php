<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\LastParkedLocationController;
use App\Http\Controllers\RefuelingController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\LogoutController;
use App\Http\Controllers\User\AccountManagementController;
use App\Http\Controllers\User\AvatarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController;
use App\Models\Inspection;
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

Route::get('/verified-notice', function () {
    return redirect('https://egarage.store/login');
})->name('loginPage');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/email/verify/{id}/{hash}', fn(EmailVerificationRequest $request) => $request->fulfill())
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');

Route::middleware(['cors'])->prefix('/v1/auth')->group(function ()
{
    Route::post('/signup', [RegisterController::class, 'create'])
        ->middleware('validate.register')
        ->name('register');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('validate.login')
        ->name('login');

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
});

Route::middleware(['cors'])->prefix('/v1/reset-password')->group(function ()
{
    Route::put('/send-link', [ResetPasswordController::class, 'sendResetLink'])->middleware('validate.send.reset.link');
    Route::put('/', [ResetPasswordController::class, 'resetPassword'])->middleware('validate.reset.password')->name('password.reset');
});

Route::prefix('/v1/account')
->middleware(['auth:sanctum', 'verified:loginPage','cors'])
->group(function ()
{
    Route::prefix('/update')->group(function ()
    {
        Route::put('/password', [AccountManagementController::class, 'updatePassword'])->middleware('validate.update.password');
        Route::put('/email', [AccountManagementController::class, 'updateEmail'])->middleware('validate.update.email');
        Route::put('/name', [AccountManagementController::class, 'updateName'])->middleware('validate.update.name');
    });

    Route::prefix('/avatar')->group(function ()
    {
        Route::get('/', [AvatarController::class, 'get']);
        Route::post('/upload', [AvatarController::class, 'upload'])->middleware('validate.upload.avatar');
        Route::delete('/delete', [AvatarController::class, 'delete']);
    });
});

Route::prefix('/v1/cars')
->middleware(['auth:sanctum', 'verified:loginPage','cors'])
->group(function ()
{
    Route::post('/add', [CarController::class, 'create'])->middleware('validate.create.car');
    Route::get('/', [CarController::class, 'get']);
    Route::put('/update/{car}', [CarController::class, 'update'])->middleware('validate.update.car');
    Route::put('/update/details/{car}', [CarController::class, 'updateDetails'])->middleware('validate.update.car.details');
    Route::post('/status/{car}', [CarController::class, 'status']);
    Route::delete('/delete/{car}', [CarController::class, 'delete']);

    Route::prefix('/insurance/{car}')
    ->group(function ()
    {
        Route::get('/', [InsuranceController::class, 'get']);
        Route::put('/update', [InsuranceController::class, 'update'])->middleware('validate.update.car.insurance');
        Route::delete('/delete', [InsuranceController::class, 'delete']);
    });

    Route::prefix('/inspection/{car}')
    ->group(function ()
    {
        Route::get('/', [InspectionController::class, 'get']);
        Route::put('/update', [InspectionController::class, 'update'])->middleware('validate.update.car.inspection');
        Route::delete('/delete', [InspectionController::class, 'delete']);
    });
});
Route::prefix('/v1/refueling')
    ->middleware(['auth:sanctum', 'verified:loginPage','cors'])
    ->group(function (){
        Route::post('/add', [RefuelingController::class, 'create'])->middleware('validate.create.refueling');
        Route::get('/', [RefuelingController::class, 'get']);
        Route::put('/update/{refueling}', [RefuelingController::class, 'update'])->middleware('validate.update.refueling');
        Route::delete('/delete/{refueling}', [RefuelingController::class, 'delete']);
    });

Route::prefix('/v1/car-budget/{budget}')
->middleware(['auth:sanctum', 'verified:loginPage','cors'])
->group(function ()
{
    Route::get('/', [BudgetController::class, 'get']);
    Route::put('/update/original-budget', [BudgetController::class, 'updateOriginalBudget'])->middleware('validate.update.original.budget');
    Route::put('/update/last-payment', [BudgetController::class, 'updateLastPayment'])->middleware('validate.update.last.payment');
    Route::delete('/delete', [BudgetController::class, 'delete']);
});

Route::prefix('/v1/last-parked-location')
->middleware(['auth:sanctum', 'verified:loginPage','cors'])
->group(function ()
{
    Route::get('/', [LastParkedLocationController::class, 'get']);
    Route::post('/set', [LastParkedLocationController::class, 'set'])->middleware('validate.set.location');
    Route::delete('/delete', [LastParkedLocationController::class, 'delete']);
});

Route::prefix('/v1/family-sharing')
->middleware(['auth:sanctum','cors'])
->group(function ()
{
    Route::get('/', [FamilyController::class, 'get']);
    Route::get('/{family}', [FamilyController::class, 'show']);
    Route::post('/create', [FamilyController::class, 'create'])->middleware('validate.create.family');
    Route::put('/update/{family}', [FamilyController::class, 'updateDetails'])->middleware('validate.update.family');
    Route::put('/update/{family}/members', [FamilyController::class, 'updateMembers'])->middleware('validate.update.family.members');
    Route::put('/update/{family}/cars', [FamilyController::class, 'updateCars'])->middleware('validate.update.family.cars');
    Route::put('/update/{family}/{car}/detach', [FamilyController::class, 'detachCar']);
    Route::delete('/delete/{family}', [FamilyController::class, 'delete']);
});
