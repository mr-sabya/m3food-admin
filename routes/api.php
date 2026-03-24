<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);      // GET /api/products
    Route::get('/featured', [ProductController::class, 'featured']); // GET /api/products/featured
    Route::get('/{slug}', [ProductController::class, 'show']); // GET /api/products/{slug}
});
