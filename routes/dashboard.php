<?php

use App\Http\Controllers\API\Dashboard\ActivityController;
use App\Http\Controllers\API\Dashboard\AdController;
use App\Http\Controllers\API\Dashboard\BookingController;
use App\Http\Controllers\API\Dashboard\CategoryController;
use App\Http\Controllers\API\Dashboard\ClientController;
use App\Http\Controllers\API\Dashboard\HomeController;
use App\Http\Controllers\API\Dashboard\JoinRequestController;
use App\Http\Controllers\API\Dashboard\PaymentController;
use App\Http\Controllers\API\Dashboard\PolicyController;
use App\Http\Controllers\API\Dashboard\PricingController;
use App\Http\Controllers\API\Dashboard\ServiceController;
use App\Http\Controllers\API\Dashboard\ServiceProviderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/home', HomeController::class);

    Route::get('/activities', ActivityController::class);

    Route::get('/join-requests', [JoinRequestController::class, 'index']);
    Route::put('/join-requests/{id}', [JoinRequestController::class, 'update']);

    Route::apiResource('/clients', ClientController::class);

    Route::apiResource('/service-providers', ServiceProviderController::class);

    Route::apiResource('/packages', PricingController::class);

    Route::apiResource('/services', ServiceController::class);

    Route::apiResource('/bookings', BookingController::class);

    Route::apiResource('/categories', CategoryController::class);

    Route::apiResource('/payments', PaymentController::class);

    Route::apiResource('/ads', AdController::class);

    Route::apiResource('/policies', PolicyController::class);

});
