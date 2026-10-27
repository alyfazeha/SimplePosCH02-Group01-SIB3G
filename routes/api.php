<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

// Login tidak memerlukan autentikasi terlebih dahulu
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

// Semua endpoint API versi 1
Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/products', [ProductController::class, 'index'])
            ->name('api.v1.products.index');

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('api.v1.categories.index');

        Route::post('/transactions', [TransactionController::class, 'store']);
    });

});