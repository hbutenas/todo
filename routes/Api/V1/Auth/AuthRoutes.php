<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// public routes
Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// private routes
Route::group(['prefix' => 'v1/auth', 'middleware' => 'auth:sanctum'], function () {
    Route::get('identify', [AuthController::class, 'identify']);
});
