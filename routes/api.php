<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::get('user', [UserController::class, 'get']);

    //// TASKS ////

    Route::prefix('tasks')->group(function () {
        Route::post('/', [TaskController::class, 'create']);
        Route::get('/', [TaskController::class, 'get']);
        Route::get('/{uid}', [TaskController::class, 'find']);
        
        Route::put('/{uid}', [TaskController::class, 'update']);
        Route::delete('/{uid}', [TaskController::class, 'delete']);
    });
});
