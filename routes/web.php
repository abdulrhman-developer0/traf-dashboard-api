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
use App\Http\Controllers\SystemTrashController;


// Route::get('/test', [HomeController::class, 'index']);
// Route::get('/test', [JoinRequestController::class, 'index']);
// Route::get('/test', [ServiceProviderController::class, 'index']);
Route::get('/test/{id}', [ServiceProviderController::class, 'show']);
// Route::get('/test', [ClientController::class, 'index']);
// Route::get('/test', [PricingController::class, 'index']);
// Route::get('/test', [ServiceController::class, 'index']);
// Route::get('/test', [BookingController::class, 'index']);
// Route::get('/test', [CategoryController::class, 'index']);


Route::get('/', function () {
    return redirect('/');
});


Route::group(['middleware' => ['auth:web','Inertia','UserLastActivity', 'can:dashboard-dashboard-view'],'prefix' => ''], function() {

    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::get('/requests', [JoinRequestController::class, 'index']);

    


    Route::middleware(['admin'])->group(function () {
 
        Route::resource('users', UsersController::class);

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');

        Route::get('/system-log', [SystemLogController::class, 'index'])->name('system-log');

        Route::get('/system-trash', [SystemTrashController::class, 'index'])->name('system-trash');


        Route::get('/under-development', function () {
            return Inertia::render('under-development');
        })->name('under-development');
        

        Route::get('/testing-abed', function () {
            return Inertia::render('testing');
        })->name('testing-abed');
    });
    


});


require __DIR__ . '/auth.php';
