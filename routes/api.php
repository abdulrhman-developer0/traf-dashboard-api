<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\ForgetPassswordController;
use App\Http\Controllers\API\Auth\ProfileController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ChatMemberController;
use App\Http\Controllers\API\ChatMessagesController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\FavoritController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\ReviewsController;
use App\Http\Controllers\API\ServiceCategoryController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ServiceOfferController;
use App\Http\Controllers\API\ServiceProviderController;
use App\Http\Controllers\API\ServiceProviderPortfolioController;
use App\Http\Controllers\API\ServiceScheduleController;
use App\Http\Controllers\API\WorkerController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UsersController;

use App\Http\Middleware\TwoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forget-password/send', [ForgetPassswordController::class, 'send']);
    Route::post('/forget-password/check', [ForgetPassswordController::class, 'check']);
    Route::post('/forget-password/reset', [ForgetPassswordController::class, 'reset']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/change-password', [ProfileController::class, 'changePassword']);

        Route::get('/profile', [ProfileController::class, 'data']);
    });
});

Route::apiResource('/clients', ClientController::class);

Route::apiResource('/service-providers', ServiceProviderController::class);
Route::get('/service-providers/{id}/partners/addresses', [ServiceProviderController::class, 'indexForAddresses']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/cities', CityController::class);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::apiResource('/workers', WorkerController::class);

    Route::apiResource('/bookings', BookingController::class);


    Route::apiResource('/reviews', ReviewsController::class);


    // chat 
    Route::apiResource('/chats', ChatController::class);

    // chat member 
    Route::apiResource('/chat-members', ChatMemberController::class);


    // chat messages 
    Route::apiResource('/chat-messages', ChatMessagesController::class);
    Route::put('/chat-messages/read/{id}', [ChatMessagesController::class, 'markAsRead']);


    // services 
    Route::get('/services/favorits', [FavoritController::class, 'index']);
    Route::post('/services/favorits', [FavoritController::class, 'taggle']);
    Route::apiResource('services', ServiceController::class);

    //Serivce Categories 
    Route::apiResource('/service-categories', ServiceCategoryController::class);

    // ServiceSchedule
    Route::apiResource('/service-schedules', ServiceScheduleController::class);

    //offers 
    Route::apiResource('/offers', OfferController::class);

    // service-offers
    Route::apiResource('/service-offers', ServiceOfferController::class);


    // portfolio
    Route::apiResource('/service-provider-portfolios', ServiceProviderPortfolioController::class);
});

// user 

Route::post('/user/create-user', [UsersController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('verify/code', [TwoFactorController::class, 'store']);
    Route::post('verify', [TwoFactorController::class, 'show']);
});

// two factor Auth middleware use alias two-factor 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum']);
