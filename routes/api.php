<?php

use App\Cart\Infrastructure\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/cart/initiate', [CartController::class, 'initiate']);

Route::post('/cart/addToCart', [CartController::class, 'addToCart']);

Route::post('/cart/removeFromCart', [CartController::class, 'removeFromCart']);

Route::post('/cart/createOrder', [CartController::class, 'createOrder']);
