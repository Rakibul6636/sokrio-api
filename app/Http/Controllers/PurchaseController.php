<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->input('product_id'));

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $availableQuantity = $product->quantity - $product->totalPurchasedQuantity();

        if ($availableQuantity < $request->input('quantity')) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $purchase = new Purchase();
        $purchase->product_id = $product->id;
        $purchase->quantity = $request->input('quantity');
        $purchase->save();

        return response()->json(['message' => 'Purchase added successfully'], 201);
    }
}
