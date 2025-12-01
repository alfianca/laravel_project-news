<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;

Route::get('/test', fn() => response()->json(['status' => 'API OK']));

// PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED ROUTES (auth required)
Route::middleware('auth:sanctum')->group(function () {

    // ADMIN-ONLY ROUTES
    Route::middleware('role:admin')->group(function () {
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{news}', [NewsController::class, 'update']);
        Route::delete('/news/{news}', [NewsController::class, 'destroy']);
    });

    // GENERAL NEWS ROUTES
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{news}', [NewsController::class, 'show']);

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);
});
