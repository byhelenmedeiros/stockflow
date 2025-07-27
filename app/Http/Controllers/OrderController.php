<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /orders
    public function index()
    {
        return response()->json(Order::with('items.product')->get(), 200);
    }

    // GET /orders/{id}
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return response()->json($order, 200);
    }

    // POST /orders
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'status'         => 'sometimes|in:pending,processing,completed,cancelled',
            'items'          => 'required|array|min:1',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

         $order = Order::create([
            'customer_name'  => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'status'         => $validated['status'] ?? 'pending',
            'total_amount'   => 0,
        ]);

         $total = 0;
        foreach ($validated['items'] as $i) {
            $product     = \App\Models\Product::findOrFail($i['product_id']);
            $unitPrice   = $product->price;
            $qty         = $i['quantity'];
            $lineTotal   = $unitPrice * $qty;
            $order->items()->create([
                'product_id'  => $product->id,
                'quantity'    => $qty,
                'unit_price'  => $unitPrice,
                'total_price' => $lineTotal,
            ]);
            $total += $lineTotal;
        }

        $order->update(['total_amount' => $total]);

        return response()->json($order->load('items.product'), 201);
    }

    // PUT /orders/{id}
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'customer_name'  => 'sometimes|required|string|max:255',
            'customer_email' => 'sometimes|required|email|max:255',
            'status'         => 'sometimes|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);
        return response()->json($order->load('items.product'), 200);
    }

    // DELETE /orders/{id}
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
