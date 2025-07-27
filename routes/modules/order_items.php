<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderItemController;

Route::get('order-items',  [OrderItemController::class, 'index']);
Route::post('order-items', [OrderItemController::class, 'store']);
Route::get('order-items/{id}', [OrderItemController::class, 'show']);
Route::put('order-items/{id}', [OrderItemController::class, 'update']);
