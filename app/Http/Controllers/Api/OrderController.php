<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        $orders = $request->user()->orders()->with('items.product')->latest()->get();

        return response()->json($orders);
    }
}
