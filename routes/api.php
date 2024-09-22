<?php

use App\Http\Controllers\Api\Auth\CustomerLoginController;
use App\Http\Controllers\Api\Auth\CustomerRegisterController;
use App\Http\Controllers\Api\Auth\CustomerResetPasswordController;
use App\Http\Controllers\Api\Auth\CustomerVerifyController;
use App\Http\Controllers\Api\Customer\City\CustomerCityController;
use App\Http\Controllers\Api\Customer\PartyCategory\PartyCategoryController;
use App\Http\Controllers\Api\Customer\PartyPreparationTime\PartyPreparationTimeController;
use App\Http\Controllers\Api\Customer\SearchParty\CustomerSearchPartyController;
use App\Http\Controllers\Api\Customer\Slider\CustomerSliderController;
use App\Http\Controllers\Api\Otp\OtpSenderController;
use App\Http\Controllers\Api\User\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function(): void{
    Route::post('register', [CustomerRegisterController::class, 'store']);
    Route::post('verify', [CustomerVerifyController::class, 'store']);
    Route::post('login', [CustomerLoginController::class, 'store']);
    Route::post('reset-password', [CustomerResetPasswordController::class, 'store']);
});

Route::prefix('otp')->group(function(): void{
    Route::post('send', [OtpSenderController::class, 'store']);
});

Route::prefix('user-profile')->group(function(): void{
    Route::get('{id}', [UserProfileController::class, 'show']);
    Route::put('update', [UserProfileController::class, 'update']);
});

// customer
Route::prefix('sliders')->group(callback: function(): void{
    Route::get('', [CustomerSliderController::class, 'index']);
});

Route::prefix('cities')->group(callback: function(): void{
    Route::get('', [CustomerCityController::class, 'index']);
});

Route::prefix('parties/categories')->group(callback: function(): void{
    Route::get('', [PartyCategoryController::class, 'index']);
});

Route::prefix('parties/preparation-times')->group(callback: function(): void{
    Route::get('', [PartyPreparationTimeController::class, 'index']);
});

Route::prefix('search-parties')->group(callback: function(): void{
    Route::get('', [CustomerSearchPartyController::class, 'index']);
});







