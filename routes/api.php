<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Division routes
    Route::get('/divisions', [App\Http\Controllers\Api\DivisionController::class, 'get']);

    // Employee routes
    Route::get('/employees', [App\Http\Controllers\Api\EmployeeController::class, 'get']);
    Route::post('/employees', [App\Http\Controllers\Api\EmployeeController::class, 'post']);
    Route::put('/employees/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'put']);
    Route::delete('/employees/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'delete']);

    // test route
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'You are authorized to access this route',
        ]);
    });
});

// Bonus routes
Route::get('/test-scores/nilai-rt', [App\Http\Controllers\Api\TestScoreController::class, 'getNilaiRT']);
Route::get('/test-scores/nilai-st', [App\Http\Controllers\Api\TestScoreController::class, 'getNilaiST']);
