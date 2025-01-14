<?php

use App\Http\Controllers\API\AdController;
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
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\PaymentController;
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
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\PaytabsController;
use App\Http\Controllers\Webhooks\PaymobWebhook;
use App\Http\Controllers\API\FcmController;
use App\Http\Controllers\API\PolicyController;
use App\Http\Middleware\TwoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/cron', function () {
    return response()->json([
        'message' => 'Cron running successfuly.'
    ]);
});

Route::get('policies', [PolicyController::class, 'index']);
Route::get('policies/{id}', [PolicyController::class, 'show']);


Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forget-password/send', [ForgetPassswordController::class, 'send']);
    Route::post('/forget-password/check', [ForgetPassswordController::class, 'check']);
    Route::post('/forget-password/reset', [ForgetPassswordController::class, 'reset']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/profile', [ProfileController::class, 'data']);
        Route::put('/profile', [ProfileController::class, 'update']);

        Route::get('/profile/reports', [ProfileController::class, 'reports']);

        Route::patch('/profile/change-photo', [ProfileController::class, 'changePhoto']);
        Route::get('/profile/{id}', [ProfileController::class, 'dataFor']);

        Route::patch('/profile/change-password', [ProfileController::class, 'changePassword']);

        Route::delete('/profile', [ProfileController::class, 'destroyAccount']);
    });
});

Route::get('/ads', [AdController::class, 'index']);
Route::post('/ads', [AdController::class, 'store']);
Route::get('/my-ads', [AdController::class, 'myAds']);
Route::post('/my-ads/pay', [AdController::class, 'payFor']);

Route::apiResource('/clients', ClientController::class);


Route::apiResource('/service-providers', ServiceProviderController::class);
Route::get('/service-providers/{id}/partners/addresses', [ServiceProviderController::class, 'indexForAddresses']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/cities', CityController::class);



    Route::get('/packages', PackageController::class)->middleware(['account:service-provider']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::apiResource('/workers', WorkerController::class);
    Route::delete('/workers/{workerId}', [WorkerController::class, 'destroy']);


    Route::patch('/bookings/change', [BookingController::class, 'change']);
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

    // Subscription routes
    Route::post('/subscriptions', [SubscriptionController::class, 'subscribe'])
        ->middleware(['account:service-provider']);

    Route::post('/payment', [PaymentController::class, 'subscribe'])
        ->middleware(['account:client']);

    // Paytabs 
    Route::post('/payments/initiate', [PaytabsController::class, 'initiatePayment']);
    Route::post('/payments/verify', [PaytabsController::class, 'verifyPayment']);

    Route::put('update-device-token', [FcmController::class, 'updateDeviceToken']);
});

Route::post('/webhooks/paymob', PaymobWebhook::class);

// PayMob webhook
Route::post('/webhooks/paymob', [SubscriptionController::class, 'handlePaymentWebhook']);
// Paymob callback.
Route::get('/callbacks/paymob', [SubscriptionController::class, 'handlePaymentWebhook']);

//client paymob 
Route::get('/callbacks/paymob', [PaymentController::class, 'handlePaymentWebhook']);

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
