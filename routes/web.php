<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orderscreate', [OrderController::class, 'show']);
Route::post('/order_store', [OrderController::class, 'store']);

Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus']);
