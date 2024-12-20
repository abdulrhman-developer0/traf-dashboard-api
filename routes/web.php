<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SystemTrashController;


Route::get('/', function () {
    return redirect('/dashboard');
});


Route::group(['middleware' => ['auth', 'can:dashboard-dashboard-view'],'prefix' => 'dashboard'], function() {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

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
