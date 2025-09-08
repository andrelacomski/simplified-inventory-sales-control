<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

/*
* Authenticated routes
*/
Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'list']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'get']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'list']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::get('/{id}', [ProductController::class, 'get']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
