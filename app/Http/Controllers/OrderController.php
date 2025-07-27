<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $items = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ])['items'];

        return DB::transaction(function () use ($items) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total' => 0
            ]);

            $total = collect($items)->reduce(function ($carry, $item) use ($order) {
                $product = Product::findOrFail($item['product_id']);
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                ]);
                $product->decrement('quantity', $item['quantity']);
                return $carry + $product->price * $item['quantity'];
            }, 0);

            $order->update(['total' => $total]);

            return $order->load('items.product');
        });
    }
}