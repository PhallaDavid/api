<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Helpers\TelegramHelper;

class OrderController extends Controller
{
    // Create new order
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|min:1',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // Calculate total price
        $totalPrice = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $totalPrice += $product->price * $item['qty'];
        }

        // Create order linked to table number
        $order = Order::create([
            'table_number' => $request->table_number,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Attach order items
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['qty'],
                'price' => $product->price,
            ]);
        }

        // Prepare Telegram message
        $message = "🛒 ការបញ្ជាទិញថ្មី៖\n";
        $message .= "លេខតុ: {$order->table_number}\n";
        $message .= "លេខរៀង: {$order->id}\n";
        $message .= "មុខម្ហូប៖\n";

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $productName = $product ? $product->name : 'មុខម្ហូបមិនស្គាល់';
            $quantity = $item['qty'];
            $message .= "- {$productName}, ចំនួន: {$quantity}\n";
        }

        $message .= "តម្លៃសរុប: \${$order->total_price}\n";

        \App\Helpers\TelegramHelper::sendMessage($message);

        // Return order with items
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items.product')
        ], 201);
    }


    // Get all orders
    public function index(Request $request)
    {
        $perPage = $request->get('row_per_page', 10);
        $page = $request->get('page', 1);

        $orders = Order::with('items.product')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($orders);
    }


    // Get orders by table number
    public function byTable($table)
    {
        $orders = Order::with('items.product')
            ->where('table_number', $table)
            ->get();

        return response()->json($orders);
    }

    // Update order status
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status ?? $order->status
        ]);

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order
        ]);
    }

    // Delete order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
