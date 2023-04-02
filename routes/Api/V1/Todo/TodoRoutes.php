<?php

use App\Http\Controllers\Api\V1\Todo\TodoController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1/todo', 'middleware' => 'auth:sanctum'], function () {
    Route::post('', [TodoController::class, 'store']);
    Route::get('', [TodoController::class, 'index']);
    Route::get('{id}', [TodoController::class, 'show'])
        ->where('id', '[1-9]+');
    Route::patch('{id}', [TodoController::class, 'update'])
        ->where('id', '[1-9]+');
    Route::delete('{id}', [TodoController::class, 'destroy'])
        ->where('id', '[1-9]+');
});
