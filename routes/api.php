<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;


Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);

Route::apiResource('/products', \App\Http\Controllers\Api\ProductController::class,);
Route::get('/categories/{id}/products', [ProductController::class, 'getByCategory']);
Route::apiResource('/subcategories', \App\Http\Controllers\Api\SubCategoryController::class);
Route::apiResource('/blogs', \App\Http\Controllers\Api\BlogController::class);
Route::apiResource('/categories', \App\Http\Controllers\Api\CategoryController::class);
    // Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    
});
