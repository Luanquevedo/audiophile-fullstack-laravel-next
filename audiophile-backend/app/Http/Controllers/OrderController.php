<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['items.product'])->latest()->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'nullable|string|in:pending,shipped,completed',
            'shipping_address' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'status' => $data['status'] ?? 'pending',
                'shipping_address' => $data['shipping_address'],
            ]);

            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $unit = $product->price;
                $total = $unit * $item['quantity'];
                $subtotal += $total;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unit,
                    'total_price' => $total,
                ]);
            }

            $order->update(['total' => $subtotal]);

            return response()->json([
                'message' => 'Pedido criado com sucesso',
                'order' => $order->load('items.product')
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('items.product')->find($id);
        if(!$order){
            return response()->json([
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $data  = $request->validate([
            'status' => 'nullable|string|in:pending,paid,shipped,delivered,canceled',
            'shipping_address' => 'nullable|string|max:255',
        ]);

        $order->update($data);

        return response()->json([
            'message' => 'Pedido atualizado com sucesso!',
            'order' => $order
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(string $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status === 'canceled') {
            return response()->json(['message' => 'Pedido já está cancelado'], 400);
        }

        $order->update(['status' => 'canceled']);

        return response()->json(['message' => 'Pedido cancelado com sucesso!']);
    }
}
