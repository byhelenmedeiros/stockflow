<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderItemController;

Route::get('order-items',  [OrderItemController::class, 'index']);
Route::post('order-items', [OrderItemController::class, 'store']);
