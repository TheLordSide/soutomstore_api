<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategorieController;


//Routes protégées

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
   /*  Route::get('/user', function (Request $request) {
        return $request->user();
    }); */
    Route::apiResource('/users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
;

//Routes publiques

Route::prefix('v1')->group(function(){
    Route::apiResource('/categories', CategorieController::class);
    Route::apiResource('/produits', ProductController::class);
    Route::get('/categories/{id}/produits', [CategorieController::class, 'getProductsByCategory']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
