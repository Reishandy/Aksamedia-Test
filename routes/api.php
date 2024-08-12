<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Division routes
    Route::get('/divisions', [App\Http\Controllers\Api\DivisionController::class, 'get']);
    Route::post('/divisions', [App\Http\Controllers\Api\DivisionController::class, 'get']);

    // Employee routes

    // test route
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'You are authorized to access this route',
        ]);
    });
});
