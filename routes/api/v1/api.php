<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TicketsController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Trzeba dodać w headers Authorization header i value Bearer i value tokena np. "Bearer 1|o4j7ClOUqmYRmDGCxZDTMBvKjUUQyhCpd4R7H3E4a6cd6460"
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('tickets', TicketsController::class);
    Route::put('tickets/{ticket', [TicketsController::class, 'replace']);

    Route::apiResource('users', UsersController::class);

    // NOTE nested
    // Route::apiResource('users.tickets', UsersController::class);
});

