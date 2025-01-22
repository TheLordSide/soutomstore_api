<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategorieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function(){
    Route::apiResource('/categories', CategorieController::class);
    Route::apiResource('/produits', ProductController::class);
    Route::get('/categories/{id}/produits', [CategorieController::class, 'getProductsByCategory']);
});
