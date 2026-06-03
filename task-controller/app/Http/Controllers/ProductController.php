<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::query()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        return Product::query()->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }

    public function reduceStock(Product $product, Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
        ]);
        $amount = $validated['amount'];
        if ($amount > $product->quantity) {
            throw ValidationException::withMessages(['amount' => 'The amount exceeds the available stock.']);
        }
        $product->update(['quantity' => $product->quantity - $amount]);

        return $product;
    }
}
