<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    // GET /order-items
    public function index()
    {
        return response()->json(\App\Models\OrderItem::with('order','product')->get(), 200);
    }

    // POST /order-items
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'    => 'required|exists:orders,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $product   = \App\Models\Product::findOrFail($validated['product_id']);
        $unitPrice = $product->price;
        $total     = $unitPrice * $validated['quantity'];

        $item = \App\Models\OrderItem::create([
            'order_id'    => $validated['order_id'],
            'product_id'  => $validated['product_id'],
            'quantity'    => $validated['quantity'],
            'unit_price'  => $unitPrice,
            'total_price' => $total,
        ]);

        // atualiza total do pedido
        $order = Order::findOrFail($validated['order_id']);
        $newTotal = $order->items()->sum('total_price');
        $order->update(['total_amount' => $newTotal]);

        return response()->json($item->load('product'), 201);
    }
}
