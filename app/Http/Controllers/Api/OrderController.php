<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|min:5',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id, // <- este valor
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'total' => $validated['total'],
            'items' => $validated['items'], 
        ]);

        return response()->json(['message' => 'Orden creada con éxito', 'order' => $order], 201);
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->latest()->get();

        $orders->transform(function ($order) {
            $order->products = collect($order->items)->map(function ($item) {
                $product = Product::find($item['product_id']);

                if (!$product) return null;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                ];
            })->filter(); // Saca los nulos si un producto no existe

            return $order;
        });

        return response()->json($orders);
    }

    public function confirm(Order $order)
    {
        $order->status = 'confirmed';
        $order->save();

        return response()->json(['message' => 'Orden confirmada con éxito.', 'order' => $order]);
    }

}
