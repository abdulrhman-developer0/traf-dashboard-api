<?php

use App\Http\Controllers\API\Dashboard\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/home', HomeController::class);
