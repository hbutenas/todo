<?php

use App\Http\Controllers\Api\V1\Todo\TodoController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1/todo', 'middleware' => 'auth:sanctum'], function () {
    Route::post('', [TodoController::class, 'create']);
});
