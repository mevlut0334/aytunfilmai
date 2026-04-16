<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SliderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Auth (Public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });


    // Public - Mobil Slider
    Route::get('/sliders', [SliderController::class, 'index']);


    // Protected (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Profil
        Route::get('/profile', [ProfileController::class, 'show']);
    });

});
