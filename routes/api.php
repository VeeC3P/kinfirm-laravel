

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->group(function () {
    // optional: protect API with Sanctum (bonus)
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('{product}', [ProductController::class, 'show']);
});