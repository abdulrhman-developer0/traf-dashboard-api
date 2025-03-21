<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Dashboard\BookingController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\JoinRequestController;
use App\Http\Controllers\Dashboard\PricingController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ServiceProviderController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\PolicyController;
use App\Http\Controllers\Dashboard\AdController;

use App\Http\Controllers\Dashboard\UserSettingsController;
use App\Http\Controllers\Dashboard\UserProfileController;


// Route::get('/test', [HomeController::class, 'index']);
// Route::get('/test', [JoinRequestController::class, 'index']);
// Route::get('/test', [ServiceProviderController::class, 'index']);
// Route::get('/test/{id}', [ServiceProviderController::class, 'show']);
// Route::get('/test', [ClientController::class, 'index']);
// Route::get('/test/{id}', [ClientController::class, 'show']);
// Route::get('/test', [PricingController::class, 'index']);
// Route::get('/test', [ServiceController::class, 'index']);
// Route::get('/test', [BookingController::class, 'index']);
// Route::get('/test', [CategoryController::class, 'index']);


// Route::get('/', function () {
//     return redirect("https://management.tarf-beauty.com");
// });

Route::get('/', function () {
    return redirect('/');
});




Route::group(['middleware' => ['auth:web','Inertia','UserLastActivity', 'can:dashboard-dashboard-view'],'prefix' => ''], function() {

    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::resource('/requests', JoinRequestController::class);

    Route::resource('/clients', ClientController::class);

    Route::resource('/service-providers', ServiceProviderController::class);

    Route::resource('/pricing', PricingController::class);

    Route::get('/bookings', [BookingController::class, 'index']);

    Route::get('/services', [ServiceController::class, 'index']);

    Route::get('/services-categories', [CategoryController::class, 'index']);

    Route::get('/payments', [PaymentController::class, 'index']);

    Route::resource('/policies', PolicyController::class);

    Route::resource('/ads', AdController::class);


    Route::resource('my-profile', UserProfileController::class);
    Route::get('/my-settings', [UserSettingsController::class, 'index'])->name('my-settings');
    Route::put('/my-settings/change-password', [UserSettingsController::class, 'changePassword'])->name('change-password');


});


require __DIR__ . '/auth.php';
