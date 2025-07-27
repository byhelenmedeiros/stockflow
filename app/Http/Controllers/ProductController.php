<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() { return Product::all(); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);
        return Product::create($data);
    }

    public function show(Product $product) { return $product; }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validated());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}