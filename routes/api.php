<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\ProfileController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ReviewsController;
use App\Http\Controllers\API\ServiceProviderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMemberController;
use App\Http\Controllers\ChatMessagesController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceOfferController;
use App\Http\Controllers\ServiceProviderPortfolioController;
use App\Http\Controllers\ServiceScheduleController;
use App\Models\ServiceProviderPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/change-password', [ProfileController::class, 'changePassword']);

        Route::get('/profile', [ProfileController::class, 'data']);
    });
});

Route::apiResource('/clients', ClientController::class);
Route::apiResource('/service-providers', ServiceProviderController::class);
Route::apiResource('/reviews', ReviewsController::class);

// chat 
Route::get('/chats', [ChatController::class, 'getAllChats']);
Route::get('/chat/{id}', [ChatController::class, 'getChat']);
Route::post('/chat/create', [ChatController::class, 'createChat']);
Route::put('/chat/update/{id}', [ChatController::class, 'updateChat']);
Route::delete('/chat/delete/{id}', [ChatController::class, 'deleteChat']);
// chat member 
Route::post('/chat-members', [ChatMemberController::class, 'addMember']);
Route::get('/chat-members/{chat_id}', [ChatMemberController::class, 'getMembers']);
Route::get('/user-chats/{user_id}', [ChatMemberController::class, 'getChatsByUser']);
Route::delete('/chat-members/{id}', [ChatMemberController::class, 'removeMember']);
Route::get('/chat-members/check', [ChatMemberController::class, 'isMember']);

// chat messages 
Route::post('/chat-messages', [ChatMessagesController::class, 'createMessage']);
Route::get('/chat-messages/{chat_id}', [ChatMessagesController::class, 'getMessages']);
Route::put('/chat-messages/read/{id}', [ChatMessagesController::class, 'markAsRead']);
Route::delete('/chat-messages/{id}', [ChatMessagesController::class, 'deleteMessage']);
Route::get('/chat-messages/unread/{chat_id}/{user_id}', [ChatMessagesController::class, 'getUnreadMessages']);
Route::put('/chat-messages/{id}', [ChatMessagesController::class, 'updateMessage']);

// services 
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('/services', [ServiceController::class, 'store']);
Route::put('/services/{id}', [ServiceController::class, 'update']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

//Serivce Categories 
Route::get('/service-categories', [ServiceCategoryController::class, 'index']);
Route::get('/service-categories/{id}', [ServiceCategoryController::class, 'show']);
Route::post('/service-categories', [ServiceCategoryController::class, 'store']);
Route::put('/service-categories/{id}', [ServiceCategoryController::class, 'update']);
Route::delete('/service-categories/{id}', [ServiceCategoryController::class, 'destroy']);

// ServiceSchedule
Route::get('/service-schedules', [ServiceScheduleController::class, 'index']);
Route::get('/service-schedules/{id}', [ServiceScheduleController::class, 'show']);
Route::post('/service-schedules', [ServiceScheduleController::class, 'store']);
Route::put('/service-schedules/{id}', [ServiceScheduleController::class, 'update']);
Route::delete('/service-schedules/{id}', [ServiceScheduleController::class, 'destroy']);

// booking 
Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

// service-offers
Route::get('/service-offers', [ServiceOfferController::class, 'index']);
Route::get('/service-offers/{id}', [ServiceOfferController::class, 'show']);
Route::post('/service-offers', [ServiceOfferController::class, 'store']);
Route::put('/service-offers/{id}', [ServiceOfferController::class, 'update']);
Route::delete('/service-offers/{id}', [ServiceOfferController::class, 'destroy']);

// portfolio
Route::get('/service-provider-portfolios', [ServiceProviderPortfolioController::class, 'index']);
Route::get('/service-provider-portfolios/{id}', [ServiceProviderPortfolioController::class, 'show']);
Route::post('/service-provider-portfolios', [ServiceProviderPortfolioController::class, 'store']);
Route::put('/service-provider-portfolios/{id}', [ServiceProviderPortfolioController::class, 'update']);
Route::delete('/service-provider-portfolios/{id}', [ServiceProviderPortfolioController::class, 'destroy']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
