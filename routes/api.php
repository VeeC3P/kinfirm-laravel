

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UserMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;

// Get API Tokens
Route::withoutMiddleware([UserMiddleware::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/logout', [AuthController::class, 'logout']);
});
// Protected API Token
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// The Rest of the Endpoints
Route::prefix('products')->name('products.')->middleware([UserMiddleware::class])->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{product}', [ProductController::class, 'show']);
});